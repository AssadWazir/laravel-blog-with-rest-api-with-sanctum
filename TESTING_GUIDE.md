# PHPUnit Testing Guide for Blog Project

## What is PHPUnit?

**PHPUnit** is a testing framework for PHP that allows developers to write automated tests to verify their code works correctly. It's the standard testing framework for Laravel applications.

### Key Concepts:

1. **Unit Tests**: Test individual functions/methods in isolation
   - Fast to run
   - Test business logic
   - Don't interact with database

2. **Feature Tests**: Test complete user workflows
   - Test HTTP requests/responses
   - Interact with database
   - Test authentication and authorization
   - Test user interactions

3. **Test Assertions**: Verify expected outcomes
   - `assertEquals()` - Check if values match
   - `assertTrue()` - Check if condition is true
   - `assertStatus()` - Check HTTP response code
   - `assertJson()` - Check JSON response structure

---

## Project Components & Testing Strategy

### 1. **Authentication & User Management**
- Test user registration
- Test user login/logout
- Test password reset
- Test role-based access (admin vs user)

### 2. **Blog Posts (CRUD Operations)**
- Test creating posts
- Test reading posts
- Test updating posts
- Test deleting posts
- Test authorization (users can only edit their own posts)

### 3. **User Dashboard**
- Test dashboard displays user data
- Test dashboard shows user's posts

### 4. **Admin Panel**
- Test admin can view all users
- Test admin can view all posts
- Test admin can delete users
- Test admin can delete posts
- Test non-admins cannot access admin panel

### 5. **Home Page (Public)**
- Test home page displays latest posts
- Test unauthenticated users can view posts
- Test navigation shows correct links based on auth status

---

## How to Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run with coverage report
php artisan test --coverage

# Run in verbose mode
php artisan test --verbose
```

---

## Test File Structure

### Feature Tests (tests/Feature/)
- `Auth/` - Authentication tests
- `Admin/` - Admin functionality tests
- `User/` - User functionality tests
- `HomePageTest.php` - Public page tests

### Unit Tests (tests/Unit/)
- `Models/` - Model logic tests
- `Services/` - Business logic tests

