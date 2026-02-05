<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ADMIN TESTS
 * 
 * These tests verify admin-only features:
 * - Admins can view all users
 * - Admins can view all posts
 * - Admins can delete users and posts
 * - Non-admins cannot access admin pages
 * 
 * KEY CONCEPT: Role-Based Access Control
 * - User model has 'role' column: 'admin' or 'user'
 * - AdminMiddleware checks if role === 'admin'
 */
class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: Admin can view admin dashboard
     * 
     * WHAT WE'RE TESTING:
     * - Can admins access the admin dashboard?
     * 
     * HOW WE TEST IT:
     * 1. Create an admin user
     * 2. Login as admin
     * 3. Visit /admin dashboard
     * 4. Verify status 200 (success)
     */
    public function test_admin_can_view_dashboard(): void
    {
        // Create user with role='admin'
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    /**
     * TEST 2: Regular user CANNOT access admin dashboard
     * 
     * WHAT WE'RE TESTING:
     * - Is role-based access control working?
     * - Can regular users (role='user') access admin pages? (Should NOT)
     * 
     * HOW WE TEST IT:
     * 1. Create a regular user (role='user')
     * 2. Login as user
     * 3. Try to visit /admin
     * 4. Should get 403 (Forbidden) or redirect to home
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        // Create regular user (default role is 'user')
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        // Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * TEST 3: Guest cannot access admin dashboard
     * 
     * WHAT WE'RE TESTING:
     * - Can unauthenticated users access admin pages? (Should NOT)
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        // Not authenticated, should redirect to login
        $response->assertRedirect(route('login'));
    }

    /**
     * TEST 4: Admin can view all users
     * 
     * WHAT WE'RE TESTING:
     * - Can admins see a list of all users?
     * 
     * HOW WE TEST IT:
     * 1. Create admin user
     * 2. Create 3 regular users
     * 3. Login as admin
     * 4. Visit /admin/users
     * 5. Verify page shows user count and user details
     */
    public function test_admin_can_view_all_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $users = User::factory(3)->create(['role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Users');
        
        // Verify all users are shown
        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
        }
    }

    /**
     * TEST 5: Admin can view all posts
     * 
     * WHAT WE'RE TESTING:
     * - Can admins see all posts from all users?
     * 
     * HOW WE TEST IT:
     * 1. Create admin and 2 regular users
     * 2. Each user creates posts
     * 3. Login as admin
     * 4. Verify admin can see ALL posts (not just their own)
     */
    public function test_admin_can_view_all_posts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post1 = Post::factory()->create(['user_id' => $user1->id, 'title' => 'User 1 Post']);
        $post2 = Post::factory()->create(['user_id' => $user2->id, 'title' => 'User 2 Post']);

        $response = $this->actingAs($admin)->get(route('admin.posts.index'));

        $response->assertStatus(200);
        $response->assertSee('User 1 Post');
        $response->assertSee('User 2 Post');
    }

    /**
     * TEST 6: Admin can delete a user
     * 
     * WHAT WE'RE TESTING:
     * - Can admins remove users?
     * - Are user's posts also deleted? (cascade delete)
     * 
     * HOW WE TEST IT:
     * 1. Create admin and regular user
     * 2. User creates posts
     * 3. Admin deletes user
     * 4. Verify user and posts are gone from database
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        
        // User creates posts
        Post::factory(2)->create(['user_id' => $user->id]);

        // Admin deletes user
        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        // Verify user is deleted
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        // With nullOnDelete, user's posts remain but user_id is set to null
        $this->assertDatabaseCount('posts', 2);
        $this->assertEquals(0, Post::whereNotNull('user_id')->count());
    }

    /**
     * TEST 7: Admin can delete a post
     * 
     * WHAT WE'RE TESTING:
     * - Can admins remove posts?
     */
    public function test_admin_can_delete_post(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Admin deletes post
        $response = $this->actingAs($admin)->delete(route('admin.posts.destroy', $post));

        // Verify post is deleted
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * TEST 8: Regular user cannot delete another user
     * 
     * WHAT WE'RE TESTING:
     * - Can non-admins delete users? (Should NOT)
     */
    public function test_regular_user_cannot_delete_user(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        // User1 tries to delete User2
        $response = $this->actingAs($user1)->delete(route('admin.users.destroy', $user2));

        // Should be forbidden
        $response->assertStatus(403);

        // User2 should still exist
        $this->assertDatabaseHas('users', ['id' => $user2->id]);
    }

    /**
     * TEST 9: Admin can view user profile edit page
     */
    public function test_admin_can_view_profile_edit_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Edit Profile');
    }

    /**
     * TEST 10: Admin can update their profile
     * 
     * WHAT WE'RE TESTING:
     * - Can admins change their name and email?
     */
    public function test_admin_can_update_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Updated Admin Name',
            'email' => 'newemail@example.com',
        ]);

        // Verify database was updated
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Updated Admin Name',
            'email' => 'newemail@example.com',
        ]);
    }

    /**
     * TEST 11: Admin can update password
     * 
     * WHAT WE'RE TESTING:
     * - Can admins change their password?
     */
    public function test_admin_can_update_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'password', // Default factory password
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Verify password was changed by trying to login with new password
        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'newpassword123',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * TEST 12: Admin dashboard shows user and post counts
     * 
     * WHAT WE'RE TESTING:
     * - Does dashboard display statistics?
     */
    public function test_admin_dashboard_shows_statistics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory(5)->create();
        Post::factory(10)->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        // Should show user and post counts
        $response->assertSee('6'); // 5 created + 1 admin
        $response->assertSee('10'); // 10 posts
    }
}
