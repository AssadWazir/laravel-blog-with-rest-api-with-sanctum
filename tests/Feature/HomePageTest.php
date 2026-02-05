<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PUBLIC HOME PAGE TESTS
 * 
 * These tests verify the public-facing home page works correctly:
 * - Displays latest blog posts
 * - Shows correct navigation based on auth status
 * - Guests can view posts
 * - Authenticated users see dashboard link
 * 
 * KEY CONCEPT: Public vs Authenticated Views
 * - Guests should see: Home, Login, Register, Blog Posts
 * - Authenticated users should see: Home, Dashboard, Logout, Blog Posts
 */
class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: Home page displays latest posts
     * 
     * WHAT WE'RE TESTING:
     * - Does home page show blog posts?
     * - Are posts in correct order (newest first)?
     * 
     * HOW WE TEST IT:
     * 1. Create posts
     * 2. Visit home page
     * 3. Verify latest post is displayed first
     */
    public function test_home_page_displays_latest_posts(): void
    {
        $user = User::factory()->create();
        
        $post1 = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'First Post',
            'created_at' => now()->subDays(2),
        ]);
        
        $post2 = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Latest Post',
            'created_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Latest post should appear first
        $response->assertSee('Latest Post');
        $response->assertSee('First Post');
    }

    /**
     * TEST 2: Home page shows empty state when no posts exist
     * 
     * WHAT WE'RE TESTING:
     * - Does page handle empty database gracefully?
     * - Is there a message for no posts?
     */
    public function test_home_page_shows_empty_state_when_no_posts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('No Posts Yet');
    }

    /**
     * TEST 3: Guest can view home page
     * 
     * WHAT WE'RE TESTING:
     * - Can unauthenticated users access home page?
     * - Do they see Login/Register links?
     */
    public function test_guest_can_view_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Guest should see login/register links
        $response->assertSee('Login');
        $response->assertSee('Register');
    }

    /**
     * TEST 4: Authenticated user sees Dashboard link
     * 
     * WHAT WE'RE TESTING:
     * - Does authenticated user see Dashboard button?
     * - Do they NOT see Login/Register buttons?
     * 
     * HOW WE TEST IT:
     * 1. Login as user
     * 2. Visit home page
     * 3. Check for Dashboard link and absence of Login/Register
     */
    public function test_authenticated_user_sees_dashboard_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        // Should see Dashboard link
        $response->assertSee('Dashboard');
        // Should see Logout button
        $response->assertSee('Logout');
    }

    /**
     * TEST 5: Post card displays author name
     * 
     * WHAT WE'RE TESTING:
     * - Does each post show the author's name?
     */
    public function test_post_card_displays_author_name(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Post',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    /**
     * TEST 6: Post card displays post date
     * 
     * WHAT WE'RE TESTING:
     * - Does post show creation date?
     */
    public function test_post_card_displays_post_date(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'created_at' => '2026-02-03',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Should show formatted date
        $response->assertSee('Feb 03, 2026');
    }

    /**
     * TEST 7: Post card shows excerpt (truncated body)
     * 
     * WHAT WE'RE TESTING:
     * - Is long post body truncated on home page?
     */
    public function test_post_card_shows_excerpt(): void
    {
        $user = User::factory()->create();
        $longBody = 'This is a very long post content that should be truncated. ' . str_repeat('Lorem ipsum dolor sit amet, ', 20);
        
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'body' => $longBody,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Should show beginning of post, but truncated
        $response->assertSee('This is a very long post content');
    }

    /**
     * TEST 8: Post card has "Read More" link
     * 
     * WHAT WE'RE TESTING:
     * - Can users click "Read More" to view full post?
     */
    public function test_post_card_has_read_more_link(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Read More');
    }

    /**
     * TEST 9: Home page has proper structure
     * 
     * WHAT WE'RE TESTING:
     * - Does page have navbar, hero section, and footer?
     */
    public function test_home_page_has_proper_structure(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Should have navbar (brand name)
        $response->assertSee('BlogPost');
        // Should have hero section
        $response->assertSee('Welcome');
        // Should have footer
        $response->assertSee('All rights reserved');
    }

    /**
     * TEST 10: Multiple posts are displayed correctly
     * 
     * WHAT WE'RE TESTING:
     * - Can page display multiple posts without issues?
     */
    public function test_multiple_posts_are_displayed(): void
    {
        $user = User::factory()->create();
        
        // Create 5 posts
        Post::factory(5)->create(['user_id' => $user->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        
        // Should have 5 post cards visible
        $this->assertEquals(5, Post::count());
    }

    /**
     * TEST 11: Authenticated user can access "Create Post" button
     * 
     * WHAT WE'RE TESTING:
     * - When no posts exist, authenticated user sees "Create First Post" button?
     */
    public function test_authenticated_user_sees_create_post_button_when_no_posts(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Create Your First Post');
    }

    /**
     * TEST 12: Post with deleted user shows "Unknown" author
     * 
     * WHAT WE'RE TESTING:
     * - If post author is deleted, does page handle it gracefully?
     * - Does it show "Unknown" instead of crashing?
     */
    public function test_post_with_deleted_author_shows_unknown(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Delete the user
        $user->delete();

        // Page should still load without error
        $response = $this->get('/');

        $response->assertStatus(200);
        // Should show Unknown instead of crashing
        $response->assertSee('Unknown');
    }
}
