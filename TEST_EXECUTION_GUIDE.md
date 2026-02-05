# PHPUnit Testing - Complete Guide

## What is PHPUnit?

**PHPUnit** is a testing framework for PHP that allows you to write automated tests. Instead of manually testing every feature, you write code that automatically verifies everything works.

### Real-World Analogy

Think of it like a quality control inspector in a factory:
- **Without tests**: You manually check every product
- **With tests**: A robot automatically checks every product and alerts you to issues

---

## Types of Tests in Our Project

### 1. **Feature Tests** (tests/Feature/)
Tests complete user workflows through HTTP requests.

```
✅ Tests what a real user would do:
  - Click login button → enter credentials → see dashboard
  - Create a new post → edit it → delete it
  - Navigate through pages
  
⏱️ Slower (interact with database and HTTP)
🎯 Most valuable for catching bugs
```

**Example scenarios:**
- User registration and login
- Creating/editing/deleting posts
- Admin operations
- Public home page display

### 2. **Unit Tests** (tests/Unit/)
Tests individual functions/methods in isolation.

```
✅ Tests small pieces of code:
  - Model methods
  - Database relationships
  - Business logic
  
⚡ Fast (no HTTP, minimal database)
🎯 Good for testing edge cases
```

**Example scenarios:**
- User.posts relationship
- Password hashing
- Attribute validation

### 3. **Test Assertions**
Assertions are "checks" that verify expected behavior.

```php
// Check if response status is 200 (success)
$response->assertStatus(200);

// Check if response contains text
$response->assertSee('Hello World');

// Check if user is authenticated
$this->assertAuthenticated();

// Check if record exists in database
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// Check if record does NOT exist
$this->assertDatabaseMissing('users', ['id' => 999]);

// Check if values are equal
$this->assertEquals(5, $count);
```

---

## How to Run Tests

### 1. Run All Tests
```bash
php artisan test
```

### 2. Run Specific Test File
```bash
# Run all authentication tests
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run all admin tests
php artisan test tests/Feature/Admin/AdminControllerTest.php

# Run all home page tests
php artisan test tests/Feature/HomePageTest.php
```

### 3. Run Specific Test Method
```bash
# Run only the login test
php artisan test --filter test_users_can_authenticate_using_the_login_screen
```

### 4. Run Tests with Verbose Output
```bash
# Shows detailed output of each test
php artisan test --verbose
```

### 5. Run Tests with Coverage Report
```bash
# Shows which code lines are tested
php artisan test --coverage
```

### 6. Run Only Failing Tests
```bash
php artisan test --failed
```

---

## Project-Specific Tests

### Authentication Tests
**File:** `tests/Feature/Auth/AuthenticationTest.php`

Tests covered:
- ✅ User can view login page
- ✅ User can login with valid credentials
- ✅ User cannot login with wrong password
- ✅ User can logout
- ✅ Authenticated user cannot view login page
- ✅ User cannot login with non-existent email

**How to run:**
```bash
php artisan test tests/Feature/Auth/AuthenticationTest.php
```

---

### User Posts Tests (CRUD)
**File:** `tests/Feature/User/PostControllerTest.php`

Tests covered:
- ✅ User can create post
- ✅ User can view their posts
- ✅ User can edit their posts
- ✅ User CANNOT edit another user's posts
- ✅ User can delete their posts
- ✅ User CANNOT delete another user's posts
- ✅ Validation: Post requires title and body

**How to run:**
```bash
php artisan test tests/Feature/User/PostControllerTest.php
```

**What's being tested:** Authorization & CRUD operations

---

### Admin Tests
**File:** `tests/Feature/Admin/AdminControllerTest.php`

Tests covered:
- ✅ Admin can view dashboard
- ✅ Regular user CANNOT view admin dashboard
- ✅ Admin can view all users
- ✅ Admin can view all posts
- ✅ Admin can delete users
- ✅ Admin can delete posts
- ✅ Admin can update their profile
- ✅ Admin can change password

**How to run:**
```bash
php artisan test tests/Feature/Admin/AdminControllerTest.php
```

**What's being tested:** Role-based access control

---

### Home Page Tests
**File:** `tests/Feature/HomePageTest.php`

Tests covered:
- ✅ Home page displays latest posts
- ✅ Home page shows empty state when no posts
- ✅ Guest can view home page
- ✅ Authenticated user sees Dashboard link
- ✅ Post cards display author name and date
- ✅ Multiple posts display correctly

**How to run:**
```bash
php artisan test tests/Feature/HomePageTest.php
```

**What's being tested:** Public-facing functionality

---

### Model Tests
**File:** `tests/Unit/Models/ModelTest.php`

Tests covered:
- ✅ Post belongs to User
- ✅ User has many posts
- ✅ Deleting user deletes their posts (cascade)
- ✅ User password is hashed
- ✅ User email is unique

**How to run:**
```bash
php artisan test tests/Unit/Models/ModelTest.php
```

**What's being tested:** Model relationships and attributes

---

## Test Database Configuration

Tests use an **in-memory SQLite database** (see `phpunit.xml`):

```xml
<php>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

**Benefits:**
- ✅ Tests don't touch your real database
- ✅ Very fast
- ✅ Tests are isolated (clean state before each test)
- ✅ Can run tests safely in production/development

---

## Understanding Test Structure

### RefreshDatabase Trait
```php
use RefreshDatabase;
```

This trait:
- Resets the database before each test
- Ensures clean state
- Rolls back changes after test
- Makes tests independent

---

## Test Naming Convention

Good test names describe what is being tested:

```php
// ❌ Bad names
test_login()
test_post()
test_admin()

// ✅ Good names
test_user_can_login_with_valid_credentials()
test_user_cannot_edit_another_users_post()
test_admin_can_view_all_users()
```

**Pattern:** `test_[action]_[expected_result]()`

---

## Common Assertions Cheat Sheet

```php
// HTTP Response
$response->assertStatus(200);                    // Status code
$response->assertRedirect('/path');              // Redirects
$response->assertSee('Text');                    // Contains text
$response->assertJson($data);                    // JSON response

// Authentication
$this->assertAuthenticated();                    // User is logged in
$this->assertGuest();                            // User is NOT logged in
$this->assertAuthenticatedAs($user);             // Specific user logged in

// Database
$this->assertDatabaseHas('users', [...]);        // Record exists
$this->assertDatabaseMissing('users', [...]);    // Record doesn't exist
$this->assertDatabaseCount('posts', 5);          // Count matches

// General
$this->assertEquals($expected, $actual);         // Values match
$this->assertTrue($value);                       // Is true
$this->assertFalse($value);                      // Is false
$this->assertNotNull($value);                    // Not null
```

---

## Tips for Writing Good Tests

### 1. One Assertion Per Test (When Possible)
```php
// ❌ Don't do this - multiple assertions
public function test_user_can_login_and_see_posts() {
    // Login code
    $this->assertAuthenticated();
    // View posts code
    $response->assertSee('Post');
}

// ✅ Better - separate tests
public function test_user_can_login() { ... }
public function test_user_can_see_posts() { ... }
```

### 2. Use Descriptive Variable Names
```php
// ❌ Unclear
$u = User::factory()->create();
$p = Post::factory()->create();

// ✅ Clear
$user = User::factory()->create();
$post = Post::factory()->create();
```

### 3. Arrange-Act-Assert Pattern
```php
public function test_user_can_create_post() {
    // ARRANGE: Set up test data
    $user = User::factory()->create();
    
    // ACT: Perform the action
    $response = $this->actingAs($user)
                     ->post(route('posts.store'), [...]);
    
    // ASSERT: Verify the result
    $this->assertDatabaseHas('posts', [...]);
}
```

---

## Debugging Failed Tests

### Run with Verbose Output
```bash
php artisan test --verbose
```

### Run Specific Test
```bash
php artisan test --filter test_user_can_login
```

### Check Database State
Add to test to inspect data:
```php
// Print all posts
dd(Post::all());

// Print query results
dump(User::where('email', 'test@test.com')->first());
```

---

## Next Steps

1. **Run All Tests**
   ```bash
   php artisan test
   ```

2. **Read Test Output**
   - ✅ = Test passed
   - ❌ = Test failed
   - ⊘ = Test skipped

3. **Write New Tests**
   - Identify feature you want to test
   - Write test that describes behavior
   - Run test (it will fail)
   - Write code to make test pass
   - Run test again (should pass)

This is called **Test-Driven Development (TDD)**!

---

## Summary

| Type | Speed | Use For | File Location |
|------|-------|---------|---------------|
| **Feature** | Slow | User workflows | tests/Feature/ |
| **Unit** | Fast | Model logic | tests/Unit/ |
| **Integration** | Medium | Multiple components | tests/Feature/ |

**Golden Rule:** Write tests as you develop. Don't wait until the end!

