<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AUTHENTICATION TESTS
 * 
 * These tests verify the login/logout and authentication flows work correctly.
 * 
 * Key Testing Concepts:
 * - RefreshDatabase: Resets database before each test (clean state)
 * - assertAuthenticated(): Checks if user is logged in
 * - assertGuest(): Checks if user is NOT logged in
 * - actingAs($user): Login as a specific user for testing
 * - post/get/put/delete: Simulate HTTP requests
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: User can view login page
     * 
     * WHAT WE'RE TESTING:
     * - Can unauthenticated users access the login page?
     * 
     * HOW WE TEST IT:
     * 1. Make a GET request to /login
     * 2. Check response status is 200 (success)
     * 
     * ASSERTION: assertStatus(200) - Page loads successfully
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * TEST 2: User can login with valid credentials
     * 
     * WHAT WE'RE TESTING:
     * - Can users login with correct email and password?
     * - Does the system redirect to dashboard after login?
     * 
     * HOW WE TEST IT:
     * 1. Create a test user (factory generates one)
     * 2. Make POST request to /login with user's credentials
     * 3. Check user is now authenticated
     * 4. Check response redirects to dashboard
     * 
     * ASSERTIONS:
     * - assertAuthenticated(): User is logged in
     * - assertRedirect(): Response redirects to dashboard
     */
    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // Create a test user in the database
        $user = User::factory()->create();

        // Simulate user clicking "Login" button and submitting form
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', // Default factory password
        ]);

        // DEBUG: write response to log for troubleshooting
        file_put_contents(storage_path('logs/test_login_response.html'), $response->getContent());

        // Verify user is now logged in
        $this->assertAuthenticated();
        
        // Verify user was redirected to dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    /**
     * TEST 3: User CANNOT login with wrong password
     * 
     * WHAT WE'RE TESTING:
     * - Does the system reject invalid passwords?
     * - Are unauthorized users not authenticated?
     * 
     * HOW WE TEST IT:
     * 1. Create a user with known password
     * 2. Try to login with WRONG password
     * 3. Verify login failed (user is still a guest)
     * 
     * ASSERTION:
     * - assertGuest(): User is NOT authenticated (not logged in)
     */
    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        // Create user with correct password "password"
        $user = User::factory()->create();

        // Try to login with wrong password
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // User should NOT be authenticated
        $this->assertGuest();
    }

    /**
     * TEST 4: User can logout
     * 
     * WHAT WE'RE TESTING:
     * - Can logged-in users logout?
     * - Are they no longer authenticated after logout?
     * 
     * HOW WE TEST IT:
     * 1. Create a user and login (actingAs)
     * 2. Make POST request to /logout
     * 3. Verify user is no longer authenticated
     * 4. Verify redirect to home page
     * 
     * KEY CONCEPT: actingAs($user)
     * - This automatically logs in the user for the test
     * - We don't need to enter password, we just "pretend" they're logged in
     */
    public function test_users_can_logout(): void
    {
        // Create user and automatically login
        $user = User::factory()->create();

        // Make logout request while logged in
        $response = $this->actingAs($user)->post('/logout');

        // User should NOT be authenticated anymore
        $this->assertGuest();
        
        // User should be redirected to home page
        $response->assertRedirect('/');
    }

    /**
     * TEST 5: User cannot login with non-existent email
     * 
     * WHAT WE'RE TESTING:
     * - System rejects login attempts for non-existent users
     * 
     * HOW WE TEST IT:
     * 1. Try to login with email that doesn't exist
     * 2. Verify user is NOT authenticated
     */
    public function test_user_cannot_login_with_non_existent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    /**
     * TEST 6: Authenticated user cannot view login page
     * 
     * WHAT WE'RE TESTING:
     * - Logged-in users should be redirected away from login page
     * 
     * HOW WE TEST IT:
     * 1. Login as a user
     * 2. Try to visit /login page
     * 3. System should redirect them away (to dashboard)
     */
    public function test_authenticated_user_cannot_view_login_page(): void
    {
        $user = User::factory()->create();

        // Try to visit login page while logged in
        $response = $this->actingAs($user)->get('/login');

        // Should redirect to dashboard instead
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
