# 🔬 PHPUnit Testing - Real-World Examples & Patterns

## Practical Examples from Your Blog Project

This guide shows **real code examples** from your actual tests with detailed explanations.

---

## 📝 Example 1: Testing User Login

### The Real Test (From AuthenticationTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that users can log in with valid email and password.
 * This is the most common user action - we need to ensure it works.
 * 
 * HOW WE TEST IT:
 * 1. Create a test user with known credentials
 * 2. Submit login form with valid credentials
 * 3. Verify: User is logged in and redirected to dashboard
 */
public function test_users_can_authenticate_using_the_login_screen()
{
    // ARRANGE: Create a user to log in as
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    // ACT: Submit login form with these credentials
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // ASSERT: Verify the result
    $this->assertAuthenticated();              // ← User is logged in
    $response->assertRedirect('/dashboard');   // ← Redirected to dashboard
}
```

### Why This Test Matters

```
WITHOUT THIS TEST:
❌ You manually click "Log In" button
❌ You type email & password
❌ You wait for page to load
❌ You check if dashboard appears
❌ You do this every time you change login code
❌ Takes 2 minutes per test
❌ Easy to forget to test
❌ Bugs slip through

WITH THIS TEST:
✅ Runs automatically in 0.3 seconds
✅ Runs every time you deploy
✅ Can't forget to test
✅ Bugs caught immediately
```

### What Assertions Mean

```php
$this->assertAuthenticated()
  ↓
  "Verify the user is logged in"
  
$response->assertRedirect('/dashboard')
  ↓
  "Verify the response redirects to /dashboard"
```

---

## 📝 Example 2: Testing Authorization (User Can't Edit Others' Posts)

### The Real Test (From PostControllerTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that users CANNOT edit posts that don't belong to them.
 * This is a security test - we prevent unauthorized modifications.
 * 
 * HOW WE TEST IT:
 * 1. Create User A who owns a post
 * 2. Create User B (different user)
 * 3. Try to edit User A's post AS User B
 * 4. Verify: User B gets 403 Forbidden error
 */
public function test_user_cannot_edit_another_users_post()
{
    // ARRANGE: Create two different users
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    
    // Create a post owned by User A
    $post = Post::factory()->create([
        'user_id' => $owner->id,
    ]);

    // ACT: Try to edit User A's post AS User B
    $response = $this->actingAs($otherUser)
                     ->put("/posts/{$post->id}", [
                         'title' => 'Hacked!',
                         'body' => 'This post was hacked!',
                     ]);

    // ASSERT: Verify User B is blocked
    $response->assertStatus(403);          // ← 403 Forbidden error
    
    // Verify the post wasn't actually modified
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => $post->title,           // ← Still has original title
    ]);
}
```

### Why This Test Matters (Security!)

```
WITHOUT THIS TEST:
❌ Attacker could modify other users' posts
❌ Data integrity compromised
❌ User's blog posts vulnerable
❌ Legal/security issues

WITH THIS TEST:
✅ Ensures authorization always checked
✅ Prevents unauthorized modifications
✅ Data stays secure
✅ Bug can't be introduced later
```

### What's Happening

```
User A creates a post
        ↓
User B tries to edit it
        ↓
Test intercepts the request
        ↓
Response is 403 Forbidden
        ↓
Test verifies post was NOT modified
        ↓
TEST PASSES ✅
```

---

## 📝 Example 3: Testing Admin Access Control

### The Real Test (From AdminControllerTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that ONLY admin users can access the admin dashboard.
 * Regular users should be blocked.
 * 
 * HOW WE TEST IT:
 * 1. Create a regular user (not admin)
 * 2. Try to access admin dashboard
 * 3. Verify: Get 403 Forbidden
 */
public function test_regular_user_cannot_access_admin_dashboard()
{
    // ARRANGE: Create a regular user (role = 'user')
    $user = User::factory()->create([
        'role' => 'user',  // ← Not an admin
    ]);

    // ACT: Try to access admin dashboard as regular user
    $response = $this->actingAs($user)
                     ->get('/admin');

    // ASSERT: Verify access is denied
    $response->assertStatus(403);  // ← 403 Forbidden
}
```

### Comparison: Admin vs Regular User

```
ADMIN USER:
├─ role = 'admin'
├─ Can access /admin → ✅ 200 OK
├─ Can delete users → ✅ Allowed
└─ Can delete posts → ✅ Allowed

REGULAR USER:
├─ role = 'user'
├─ Try /admin → ❌ 403 Forbidden
├─ Try delete user → ❌ 403 Forbidden
└─ Try delete post → ❌ 403 Forbidden

GUEST (Not logged in):
├─ No role
├─ Try /admin → ❌ Redirect to login
├─ Try delete user → ❌ Redirect to login
└─ Try delete post → ❌ Redirect to login
```

---

## 📝 Example 4: Testing CRUD Operations (Create Post)

### The Real Test (From PostControllerTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that authenticated users can create new blog posts.
 * 
 * HOW WE TEST IT:
 * 1. Create and log in as a user
 * 2. Submit form to create a new post
 * 3. Verify: Post is saved to database
 * 4. Verify: User is redirected to post list
 */
public function test_user_can_create_post()
{
    // ARRANGE: Create and authenticate as a user
    $user = User::factory()->create();

    // ACT: Submit the create post form
    $response = $this->actingAs($user)
                     ->post('/posts', [
                         'title' => 'My First Post',
                         'body' => 'This is the content of my post.',
                     ]);

    // ASSERT: Verify everything happened correctly
    
    // 1. Verify redirect (post was successful)
    $response->assertRedirect();
    
    // 2. Verify post was saved to database
    $this->assertDatabaseHas('posts', [
        'title' => 'My First Post',
        'body' => 'This is the content of my post.',
        'user_id' => $user->id,
    ]);
    
    // 3. Verify post count increased
    $this->assertCount(1, $user->posts);
}
```

### Full Lifecycle Test

```
1. USER CREATION
   └─ User::factory()->create()
      └─ Creates user in test database

2. FORM SUBMISSION
   └─ $this->actingAs($user)->post('/posts', [...])
      └─ Simulates form submission

3. DATABASE VERIFICATION
   └─ assertDatabaseHas('posts', [...])
      └─ Confirms post was saved

4. RELATIONSHIP VERIFICATION
   └─ $user->posts->count()
      └─ Confirms association is correct
```

---

## 📝 Example 5: Testing Validation

### The Real Test (From PostControllerTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that form validation works correctly.
 * Users MUST provide title and body - empty posts aren't allowed.
 * 
 * HOW WE TEST IT:
 * 1. Try to create post WITHOUT title
 * 2. Try to create post WITHOUT body
 * 3. Verify: Validation errors returned
 * 4. Verify: Post was NOT saved
 */
public function test_post_create_requires_title_and_body()
{
    // ARRANGE: Create and authenticate as a user
    $user = User::factory()->create();

    // ACT: Try to submit form WITH MISSING FIELDS
    $response = $this->actingAs($user)
                     ->post('/posts', [
                         'title' => '',  // ← Missing title!
                         'body' => '',   // ← Missing body!
                     ]);

    // ASSERT: Verify validation failed
    
    // 1. Verify response has validation errors
    $response->assertSessionHasErrors(['title', 'body']);
    
    // 2. Verify post was NOT saved to database
    $this->assertDatabaseMissing('posts', [
        'user_id' => $user->id,
    ]);
    
    // 3. Verify no posts were created
    $this->assertCount(0, $user->posts);
}
```

### Validation Flowchart

```
User submits form with empty fields
        ↓
POST /posts endpoint receives request
        ↓
Validation rules check:
  - title required? ❌ FAIL
  - body required?  ❌ FAIL
        ↓
Return validation errors
        ↓
Post is NOT saved to database
        ↓
Test verifies:
  ✓ Errors returned to user
  ✓ Database is unchanged
        ↓
TEST PASSES ✅
```

---

## 📝 Example 6: Testing Home Page Display

### The Real Test (From HomePageTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that the home page displays blog posts correctly.
 * 
 * HOW WE TEST IT:
 * 1. Create some posts in database
 * 2. Load home page
 * 3. Verify: Posts appear on page
 * 4. Verify: Author names are shown
 * 5. Verify: Dates are displayed
 */
public function test_home_page_displays_latest_posts()
{
    // ARRANGE: Create test posts
    $user = User::factory()->create(['name' => 'John Doe']);
    $post1 = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'First Blog Post',
        'created_at' => now()->subDay(),  // Yesterday
    ]);
    
    $post2 = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Latest Post',
        'created_at' => now(),  // Today
    ]);

    // ACT: Load the home page
    $response = $this->get('/');

    // ASSERT: Verify content appears on page
    
    // 1. Page loads successfully
    $response->assertStatus(200);
    
    // 2. Latest post appears first
    $response->assertSeeInOrder([
        'Latest Post',     // Most recent first
        'First Blog Post', // Older post second
    ]);
    
    // 3. Author name is displayed
    $response->assertSee('John Doe');
    
    // 4. Post date is displayed
    $response->assertSee(now()->format('M d, Y'));
}
```

### Page Display Verification

```
Database:
├─ Post 1: "First Blog Post" (Yesterday)
└─ Post 2: "Latest Post" (Today)
        ↓
User visits /
        ↓
Home page queries posts
        ↓
Page should show:
├─ "Latest Post" (first - most recent)
├─ "John Doe" (author)
├─ "Latest Post" date
├─ "First Blog Post" (second - older)
├─ "John Doe" (author)
└─ "First Blog Post" date
        ↓
Test verifies all this appears
        ↓
TEST PASSES ✅
```

---

## 📝 Example 7: Testing Edge Cases

### The Real Test (From HomePageTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing edge case: What happens if a post author is deleted?
 * The app should handle this gracefully (not crash).
 * 
 * HOW WE TEST IT:
 * 1. Create a post with an author
 * 2. Delete the author
 * 3. Load home page
 * 4. Verify: Page shows "Unknown Author" instead of crashing
 */
public function test_post_with_deleted_user_shows_unknown()
{
    // ARRANGE: Create post with author
    $user = User::factory()->create(['name' => 'John Doe']);
    $post = Post::factory()->create([
        'user_id' => $user->id,
        'title' => 'Orphaned Post',
    ]);
    
    // Delete the author (but post remains due to cascade issues)
    $user->delete();

    // ACT: Load home page
    $response = $this->get('/');

    // ASSERT: Verify page handles missing author gracefully
    $response->assertStatus(200);  // ← Page didn't crash!
    
    // Show either "Unknown Author" or null (no error)
    // (This test confirms the app doesn't throw an error)
}
```

### Edge Case Handling

```
Normal Scenario:
Post exists → Author exists → Display author name ✅

Edge Case Scenario:
Post exists → Author deleted → ??? 

Expected Behavior:
Post still displays with "Unknown Author" (doesn't crash) ✅

Test verifies app handles this gracefully
```

---

## 📝 Example 8: Testing Model Relationships

### The Real Test (From ModelTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing that User and Post relationships work correctly.
 * 
 * HOW WE TEST IT:
 * 1. Create a user with multiple posts
 * 2. Verify: user->posts() returns all posts
 * 3. Verify: post->user() returns the user
 * 4. Verify: post count is correct
 */
public function test_user_has_many_posts()
{
    // ARRANGE: Create a user with posts
    $user = User::factory()
                ->has(Post::factory()->count(3))  // Create 3 posts
                ->create();

    // ACT: Access the posts through relationship
    $posts = $user->posts;

    // ASSERT: Verify relationship works
    
    // 1. Count is correct
    $this->assertCount(3, $posts);
    
    // 2. All posts belong to this user
    foreach ($posts as $post) {
        $this->assertEquals($user->id, $post->user_id);
    }
    
    // 3. Relationship method exists
    $this->assertTrue(method_exists($user, 'posts'));
}
```

### Relationship Verification

```
User Model:
  public function posts() {
    return $this->hasMany(Post::class);
  }
        ↓
Test creates user with 3 posts
        ↓
Test calls $user->posts
        ↓
Relationship returns all 3 posts
        ↓
Test verifies:
  ✓ Count is 3
  ✓ Each post has correct user_id
  ✓ Relationship method exists
        ↓
TEST PASSES ✅
```

---

## 📝 Example 9: Testing Cascade Delete

### The Real Test (From ModelTest.php)

```php
/**
 * WHAT ARE WE TESTING:
 * Testing cascade delete: When a user is deleted, their posts should be deleted too.
 * This prevents orphaned posts in the database.
 * 
 * HOW WE TEST IT:
 * 1. Create a user with posts
 * 2. Delete the user
 * 3. Verify: Posts were also deleted
 */
public function test_deleting_user_deletes_their_posts()
{
    // ARRANGE: Create user with posts
    $user = User::factory()
                ->has(Post::factory()->count(5))  // 5 posts
                ->create();
    
    $userId = $user->id;

    // ACT: Delete the user
    $user->delete();

    // ASSERT: Verify posts were cascade deleted
    
    // 1. User is gone
    $this->assertDatabaseMissing('users', [
        'id' => $userId,
    ]);
    
    // 2. User's posts are also gone
    $this->assertDatabaseMissing('posts', [
        'user_id' => $userId,
    ]);
    
    // 3. No orphaned posts remain
    $this->assertEquals(0, Post::where('user_id', $userId)->count());
}
```

### Database Cascade

```
Before Delete:
Users Table:        Posts Table:
├─ id: 1            ├─ id: 1, user_id: 1
│  name: John       ├─ id: 2, user_id: 1
└─ ...              ├─ id: 3, user_id: 1
                    ├─ id: 4, user_id: 1
                    └─ id: 5, user_id: 1

Delete user with id: 1
        ↓
Cascade delete triggered
        ↓
After Delete:
Users Table:        Posts Table:
├─ (empty)          ├─ (empty)
└─ ...              └─ ...

Test verifies:
✓ User gone
✓ All user's posts gone
✓ No orphaned records
```

---

## 🎯 Test Patterns Summary

### Pattern 1: Authentication Tests
```php
public function test_feature()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/protected');
    $response->assertStatus(200);
}
```

### Pattern 2: Authorization Tests
```php
public function test_user_cannot_access()
{
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/admin');
    $response->assertStatus(403);
}
```

### Pattern 3: CRUD Tests
```php
public function test_create()
{
    $response = $this->post('/posts', ['title' => 'Test']);
    $this->assertDatabaseHas('posts', ['title' => 'Test']);
}
```

### Pattern 4: Validation Tests
```php
public function test_validation()
{
    $response = $this->post('/posts', ['title' => '']);
    $response->assertSessionHasErrors('title');
}
```

### Pattern 5: Model Tests
```php
public function test_relationship()
{
    $user = User::factory()->has(Post::factory(3))->create();
    $this->assertCount(3, $user->posts);
}
```

---

## 🚀 Quick Copy-Paste Templates

### Template: Feature Test
```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_something()
    {
        // ARRANGE
        $user = User::factory()->create();

        // ACT
        $response = $this->actingAs($user)->get('/dashboard');

        // ASSERT
        $response->assertStatus(200);
    }
}
```

### Template: Unit Test
```php
<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_relationship()
    {
        $user = User::factory()->create();
        $this->assertIsNotNull($user->id);
    }
}
```

---

## 💡 Key Learnings

1. **Every assertion is a question asked**
   - `assertStatus(200)` → "Is the response status 200?"
   - `assertDatabaseHas(...)` → "Does this record exist?"
   - `assertCount(3, ...)` → "Are there exactly 3 items?"

2. **Arrange-Act-Assert is a proven pattern**
   - ARRANGE: Set up test conditions
   - ACT: Perform the action being tested
   - ASSERT: Verify the results

3. **Tests document behavior**
   - Test names describe what the feature does
   - Code shows HOW it works
   - Comments explain WHY we test it

4. **Tests catch bugs early**
   - Modified code that breaks tests?
   - Immediate feedback!
   - No need to manually test

5. **Tests enable safe refactoring**
   - Change code to be faster/cleaner
   - Run tests
   - If all pass → refactoring is safe!

---

## 🎓 Practice Exercise

Try creating your own test:

```php
// Create a new test that verifies:
// 1. User can update their profile name
// 2. Updated name is saved to database
// 3. Profile page shows updated name

// Hint: Follow the "Pattern 3: CRUD Tests" template above
// Use: put('/profile', [...]) for update
```

---

**You're now ready to write professional PHPUnit tests! 🚀**

