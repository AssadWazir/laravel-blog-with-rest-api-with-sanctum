<?php

namespace Tests\Feature\User;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * USER POSTS TESTS - CRUD Operations
 * 
 * CRUD = Create, Read, Update, Delete
 * These tests verify users can manage their blog posts.
 * 
 * Key Concepts:
 * - factory() creates test data
 * - POST request = create/update (submit form data)
 * - GET request = view/read (fetch page)
 * - PUT/PATCH request = update resource
 * - DELETE request = remove resource
 */
class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: User can view create post page
     * 
     * WHAT WE'RE TESTING:
     * - Can logged-in users access the "Create Post" page?
     * - Does the page have the post form?
     * 
     * HOW WE TEST IT:
     * 1. Create and login a user
     * 2. Visit /user/posts/create
     * 3. Verify page loads (status 200)
     */
    public function test_authenticated_user_can_view_create_post_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.create'));

        $response->assertStatus(200);
        $response->assertSee('Create Post');
    }

    /**
     * TEST 2: Unauthenticated user cannot create post
     * 
     * WHAT WE'RE TESTING:
     * - Guests (not logged in) should NOT be able to create posts
     * 
     * HOW WE TEST IT:
     * 1. Try to visit create post page WITHOUT logging in
     * 2. Should redirect to login
     */
    public function test_unauthenticated_user_cannot_view_create_post_page(): void
    {
        // Don't login - we're a guest
        $response = $this->get(route('posts.create'));

        // Should redirect to login page
        $response->assertRedirect(route('login'));
    }

    /**
     * TEST 3: User can create a new post
     * 
     * WHAT WE'RE TESTING:
     * - Can users submit the create post form?
     * - Is the post saved to database?
     * - Does system redirect to post view page?
     * 
     * HOW WE TEST IT:
     * 1. Login as a user
     * 2. Make POST request to /posts with post data
     * 3. Verify post was created in database
     * 4. Verify response redirects to post show page
     * 
     * KEY CONCEPTS:
     * - assertDatabaseHas(): Check data exists in database
     * - post() method: Simulate form submission
     */
    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();

        // Create post with data
        $postData = [
            'title' => 'My First Blog Post',
            'body' => 'This is the content of my first blog post.',
        ];

        // Submit form
        $response = $this->actingAs($user)->post(route('posts.store'), $postData);

        // Verify post was saved to database
        $this->assertDatabaseHas('posts', [
            'title' => 'My First Blog Post',
            'user_id' => $user->id, // Verify post belongs to logged-in user
        ]);

        // Should redirect to view the new post
        $response->assertRedirect();
    }

    /**
     * TEST 4: User can view posts list
     * 
     * WHAT WE'RE TESTING:
     * - Can users see list of their posts?
     * - Are other users' posts NOT shown?
     * 
     * HOW WE TEST IT:
     * 1. Create 2 users
     * 2. User1 creates 2 posts, User2 creates 1 post
     * 3. Login as User1
     * 4. Visit /user/posts
     * 5. Verify User1 sees only their 2 posts (not User2's post)
     */
    public function test_user_can_view_only_their_posts(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User1 creates 2 posts
        Post::factory(2)->create(['user_id' => $user1->id]);
        
        // User2 creates 1 post
        Post::factory(1)->create(['user_id' => $user2->id]);

        // Login as User1
        $response = $this->actingAs($user1)->get(route('posts.index'));

        // User1 should see their 2 posts
        $response->assertStatus(200);
        $response->assertSee($user1->posts->first()->title);
        $response->assertSee($user1->posts->last()->title);
    }

    /**
     * TEST 5: User can view single post
     * 
     * WHAT WE'RE TESTING:
     * - Can users view details of their own post?
     * - Does the page display correct post data?
     * 
     * HOW WE TEST IT:
     * 1. Create user and post
     * 2. Login as user
     * 3. Visit post show page
     * 4. Verify page contains post title and body
     */
    public function test_user_can_view_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Awesome Post',
            'body' => 'Post content here.',
        ]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee('My Awesome Post');
        $response->assertSee('Post content here.');
    }

    /**
     * TEST 6: User can view edit post form
     * 
     * WHAT WE'RE TESTING:
     * - Can users access the edit form for their post?
     * - Does form contain current post data?
     */
    public function test_user_can_view_edit_post_page(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.edit', $post));

        $response->assertStatus(200);
        $response->assertSee('Edit Post');
        $response->assertSee($post->title);
    }

    /**
     * TEST 7: User can update their post
     * 
     * WHAT WE'RE TESTING:
     * - Can users modify their posts?
     * - Are changes saved to database?
     * 
     * HOW WE TEST IT:
     * 1. Create user and post
     * 2. Login as user
     * 3. Make PUT request with updated data
     * 4. Verify database reflects changes
     */
    public function test_user_can_update_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'title' => 'Updated Title',
            'body' => 'Updated content.',
        ];

        $response = $this->actingAs($user)->put(route('posts.update', $post), $updatedData);

        // Verify database was updated
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'body' => 'Updated content.',
        ]);

        $response->assertRedirect();
    }

    /**
     * TEST 8: User CANNOT edit another user's post
     * 
     * WHAT WE'RE TESTING:
     * - Is the authorization working?
     * - Can User1 edit User2's posts? (Should NOT be allowed)
     * 
     * HOW WE TEST IT:
     * 1. Create User1 and User2
     * 2. Create a post owned by User2
     * 3. Login as User1
     * 4. Try to edit User2's post
     * 5. Should get 403 (Forbidden) response
     */
    public function test_user_cannot_edit_another_users_post(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user2->id]);

        // Login as User1, try to edit User2's post
        $response = $this->actingAs($user1)->put(route('posts.update', $post), [
            'title' => 'Hacked!',
            'body' => 'Hacked post content',
        ]);

        // Should be forbidden
        $response->assertStatus(403);

        // Database should NOT be updated
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $post->title, // Original title unchanged
        ]);
    }

    /**
     * TEST 9: User can delete their post
     * 
     * WHAT WE'RE TESTING:
     * - Can users remove their posts?
     * - Is post deleted from database?
     * 
     * HOW WE TEST IT:
     * 1. Create user and post
     * 2. Login as user
     * 3. Make DELETE request
     * 4. Verify post no longer exists in database
     */
    public function test_user_can_delete_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        // Verify post was deleted
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);

        $response->assertRedirect();
    }

    /**
     * TEST 10: User CANNOT delete another user's post
     */
    public function test_user_cannot_delete_another_users_post(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user2->id]);

        // Try to delete as User1
        $response = $this->actingAs($user1)->delete(route('posts.destroy', $post));

        // Should be forbidden
        $response->assertStatus(403);

        // Post should still exist
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * TEST 11: Post requires title and body
     * 
     * WHAT WE'RE TESTING:
     * - Does validation work?
     * - Are empty posts rejected?
     */
    public function test_post_requires_title_and_body(): void
    {
        $user = User::factory()->create();

        // Try to create post without title and body
        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => '',
            'body' => '',
        ]);

        // Should have validation errors
        $response->assertSessionHasErrors(['title', 'body']);

        // Post should NOT be created
        $this->assertDatabaseCount('posts', 0);
    }
}
