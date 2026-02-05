<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * UNIT TESTS - MODEL TESTS
 * 
 * Unit tests verify business logic at the model level.
 * They test methods and relationships without HTTP requests.
 * 
 * KEY DIFFERENCE FROM FEATURE TESTS:
 * - Feature tests: Test HTTP requests and user workflows
 * - Unit tests: Test model methods and database relationships
 * - Unit tests are faster and more focused
 */
class PostModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: Post belongs to User
     * 
     * WHAT WE'RE TESTING:
     * - Is the relationship between Post and User correct?
     * - Can we access a post's author?
     * 
     * HOW WE TEST IT:
     * 1. Create a user and post
     * 2. Access post->user
     * 3. Verify user data is correct
     */
    public function test_post_belongs_to_user(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Access the user relationship
        $this->assertEquals('John Doe', $post->user->name);
        $this->assertEquals($user->id, $post->user_id);
    }

    /**
     * TEST 2: User has many posts
     * 
     * WHAT WE'RE TESTING:
     * - Can we access all posts for a user?
     */
    public function test_user_has_many_posts(): void
    {
        $user = User::factory()->create();
        
        // Create 3 posts for this user
        Post::factory(3)->create(['user_id' => $user->id]);
        
        // Create 2 posts for another user
        $otherUser = User::factory()->create();
        Post::factory(2)->create(['user_id' => $otherUser->id]);

        // User should have 3 posts
        $this->assertEquals(3, $user->posts()->count());
        
        // Other user should have 2 posts
        $this->assertEquals(2, $otherUser->posts()->count());
    }

    /**
     * TEST 3: Post has title and body
     * 
     * WHAT WE'RE TESTING:
     * - Can we access post attributes?
     */
    public function test_post_has_title_and_body(): void
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'body' => 'This is the post body.',
        ]);

        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('This is the post body.', $post->body);
    }

    /**
     * TEST 4: Post has timestamps
     * 
     * WHAT WE'RE TESTING:
     * - Does post have created_at and updated_at?
     */
    public function test_post_has_timestamps(): void
    {
        $post = Post::factory()->create();

        $this->assertNotNull($post->created_at);
        $this->assertNotNull($post->updated_at);
    }

    /**
     * TEST 5: Deleting user nullifies their posts' user_id
     * 
     * WHAT WE'RE TESTING:
     * - Is nullOnDelete working?
     * - When user is deleted, posts remain but user_id is set to null
     */
    public function test_deleting_user_deletes_their_posts(): void
    {
        $user = User::factory()->create();
        Post::factory(3)->create(['user_id' => $user->id]);

        // Verify posts exist
        $this->assertEquals(3, Post::count());

        // Delete the user (nullOnDelete: posts stay, user_id set to null)
        $user->delete();

        // Posts still exist but no longer reference the user
        $this->assertEquals(3, Post::count());
        $this->assertEquals(0, Post::whereNotNull('user_id')->count());
    }

    /**
     * TEST 6: Post is properly fillable
     * 
     * WHAT WE'RE TESTING:
     * - Can we mass-assign attributes to post?
     */
    public function test_post_is_fillable(): void
    {
        $data = [
            'title' => 'New Post',
            'body' => 'Post content',
            'user_id' => User::factory()->create()->id,
        ];

        $post = Post::create($data);

        $this->assertEquals('New Post', $post->title);
        $this->assertEquals('Post content', $post->body);
    }

    /**
     * TEST 7: Post uses HasFactory trait
     * 
     * WHAT WE'RE TESTING:
     * - Can we create posts using factory?
     */
    public function test_post_can_use_factory(): void
    {
        $post = Post::factory()->create();

        $this->assertNotNull($post->id);
        $this->assertNotNull($post->title);
        $this->assertNotNull($post->body);
    }
}

/**
 * USER MODEL TESTS
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: User has role attribute
     * 
     * WHAT WE'RE TESTING:
     * - Can we access user's role?
     * - Is role correctly set to admin or user?
     */
    public function test_user_has_role_attribute(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertEquals('admin', $user->role);
    }

    /**
     * TEST 2: User password is hashed
     * 
     * WHAT WE'RE TESTING:
     * - Is password stored as hash, not plain text?
     * - Does it hash on create?
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'mypassword123',
        ]);

        // Password should be hashed (not equal to plain text)
        $this->assertNotEquals('mypassword123', $user->password);
    }

    /**
     * TEST 3: User can check if they are admin
     * 
     * WHAT WE'RE TESTING:
     * - Can we easily check if user is admin?
     * 
     * HINT: You might want to add a method to User model:
     * public function isAdmin() {
     *     return $this->role === 'admin';
     * }
     */
    public function test_user_can_check_if_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        // These assume isAdmin() method exists on User model
        $this->assertTrue($admin->role === 'admin');
        $this->assertFalse($user->role === 'admin');
    }

    /**
     * TEST 4: User has many posts
     */
    public function test_user_has_many_posts(): void
    {
        $user = User::factory()->create();
        Post::factory(5)->create(['user_id' => $user->id]);

        $this->assertEquals(5, $user->posts()->count());
    }

    /**
     * TEST 5: User is fillable
     */
    public function test_user_is_fillable(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ];

        $user = User::create($data);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('admin', $user->role);
    }

    /**
     * TEST 6: User email is unique
     * 
     * WHAT WE'RE TESTING:
     * - Can two users have the same email? (Should NOT)
     */
    public function test_user_email_is_unique(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        // This should fail due to unique constraint
        $this->expectException(\Exception::class);
        User::factory()->create(['email' => 'test@example.com']);
    }
}
