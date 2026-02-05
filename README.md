# 🚀 Laravel API Demo - Full-Stack REST API

<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen?style=for-the-badge)]()

*A comprehensive full-stack Laravel application demonstrating REST API development with authentication, authorization, and testing best practices.*

[Features](#-features) • [Installation](#-installation) • [API Documentation](#-api-documentation) • [Testing](#-testing) • [Project Structure](#-project-structure)

</div>

---

## 📚 About This Project

**Laravel API Demo** is an educational full-stack web application built with **Laravel 12**, designed to showcase professional development patterns and best practices for building scalable REST APIs with authentication and comprehensive test coverage.

### 🎓 Learning Purpose

> ⚠️ **Note:** This project is created for **educational and learning purposes only**. It demonstrates key concepts in Laravel development including:
- RESTful API design patterns
- Token-based authentication (Sanctum)
- Role-based authorization
- Service layer architecture
- PHPUnit testing strategies
- Database relationships and migrations

---

## ✨ Features

### 🔐 Authentication & Authorization
- ✅ **User Registration & Login** - Session-based web authentication
- ✅ **Sanctum Tokens** - Stateless API authentication
- ✅ **Role-Based Access Control** - Admin and User roles
- ✅ **Post Ownership** - Users can only manage their own posts
- ✅ **Authorization Policies** - Fine-grained permission control

### 📝 Post Management (CRUD)
- ✅ **Create Posts** - Authenticated users can create posts
- ✅ **Read Posts** - Public and private post viewing
- ✅ **Update Posts** - Only post owners can edit
- ✅ **Delete Posts** - Only post owners can delete
- ✅ **Pagination** - Efficient data retrieval with pagination

### 🌐 REST API
- ✅ **Public Endpoints** - View all posts and details without authentication
- ✅ **Protected Endpoints** - Full CRUD with token authentication
- ✅ **JSON Responses** - Consistent response format
- ✅ **Proper HTTP Status Codes** - 200, 201, 403, 404, 422
- ✅ **Validation** - Request validation with detailed error messages

### 🎯 Admin Panel
- ✅ **User Management** - Create, view, edit, delete users
- ✅ **Post Management** - Admin controls over all posts
- ✅ **Admin Dashboard** - Overview and analytics

### 🧪 Testing
- ✅ **52 PHPUnit Tests** - Complete test coverage
- ✅ **Feature Tests** - HTTP request testing
- ✅ **Unit Tests** - Model and business logic testing
- ✅ **Integration Tests** - Component interaction testing
- ✅ **Authorization Tests** - Permission and access control testing

### 🎨 Frontend
- ✅ **Vite Asset Bundling** - Fast development builds
- ✅ **Tailwind CSS** - Utility-first styling
- ✅ **Alpine.js** - Lightweight interactivity
- ✅ **Blade Templates** - Dynamic server-side rendering

---

## 🏗️ Project Structure

```
api-demo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/PostController.php          # REST API endpoints
│   │   │   ├── User/PostController.php         # Web user posts CRUD
│   │   │   └── Admin/                          # Admin panel controllers
│   │   ├── Middleware/                         # Authorization middleware
│   │   └── Requests/                           # Form request validation
│   ├── Models/
│   │   ├── User.php                            # User model with relationships
│   │   └── Post.php                            # Post model
│   ├── Services/
│   │   └── PostService.php                     # Shared business logic
│   ├── Policies/
│   │   └── PostPolicy.php                      # Authorization policies
│   └── Providers/
│       └── AppServiceProvider.php              # Service provider
├── routes/
│   ├── api.php                                 # API routes
│   ├── web.php                                 # Web routes
│   ├── user.php                                # Authenticated user routes
│   ├── admin.php                               # Admin routes
│   └── auth.php                                # Authentication routes
├── database/
│   ├── migrations/                             # Database schema
│   ├── factories/                              # Model factories
│   └── seeders/                                # Database seeders
├── resources/
│   ├── js/                                     # JavaScript files
│   ├── css/                                    # CSS files
│   └── views/                                  # Blade templates
├── tests/
│   ├── Feature/                                # Feature tests
│   │   ├── Auth/AuthenticationTest.php
│   │   ├── User/PostControllerTest.php
│   │   └── Admin/AdminControllerTest.php
│   └── Unit/                                   # Unit tests
│       └── Models/ModelTest.php
├── config/                                     # Configuration files
├── storage/                                    # Logs, cache, uploads
└── public/                                     # Public assets
```

---

## 🚀 Installation

### Prerequisites
- **PHP 8.3+** with extensions: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`
- **Composer** (latest version)
- **Node.js 18+** & **npm**
- **SQLite** or **MySQL**

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/api-demo.git
cd api-demo
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
```bash
# Run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### Step 5: Build Assets
```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 6: Start Development Server
```bash
# Option 1: Single command (includes queue listener & asset watcher)
composer dev

# Option 2: Laravel development server only
php artisan serve

# Option 3: Vite dev server (separate terminal)
npm run dev
```

The application will be available at: **http://localhost:8000**

---

## 📖 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected endpoints require a Sanctum bearer token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Response Format
All API responses follow a consistent JSON structure:

**Success Response (200, 201):**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data here
  }
}
```

**Error Response (4xx, 5xx):**
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    // Validation errors (if applicable)
  }
}
```

---

### 🔓 Public Endpoints (No Authentication Required)

#### Get All Posts
```
GET /api/public/posts?per_page=15
```

**Query Parameters:**
- `per_page` (optional): Number of posts per page (default: 15)

**Response (200):**
```json
{
  "success": true,
  "message": "All posts retrieved successfully",
  "data": {
    "posts": [
      {
        "id": 1,
        "user_id": 1,
        "title": "Sample Post",
        "body": "Post content here",
        "created_at": "2026-02-05T10:30:00Z",
        "updated_at": "2026-02-05T10:30:00Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "pagination": {
      "total": 5,
      "per_page": 15,
      "current_page": 1,
      "last_page": 1
    }
  }
}
```

---

#### Get Single Post
```
GET /api/public/posts/{id}
```

**Parameters:**
- `id` (required): Post ID

**Response (200):**
```json
{
  "success": true,
  "message": "Post retrieved successfully",
  "data": {
    "post": {
      "id": 1,
      "user_id": 1,
      "title": "Sample Post",
      "body": "Post content here",
      "created_at": "2026-02-05T10:30:00Z",
      "updated_at": "2026-02-05T10:30:00Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      }
    }
  }
}
```

---

### 🔑 Authentication Endpoints

#### Register
```
POST /api/register
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "your_sanctum_token_here"
  }
}
```

---

#### Login
```
POST /api/login
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "your_sanctum_token_here"
  }
}
```

---

### 🔐 Protected Endpoints (Authentication Required)

All protected endpoints require the `Authorization: Bearer {token}` header.

#### Get Authenticated User
```
GET /api/user
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "role": "user",
  "email_verified_at": "2026-02-05T10:30:00Z",
  "created_at": "2026-02-05T10:30:00Z",
  "updated_at": "2026-02-05T10:30:00Z"
}
```

---

#### Get User's Posts
```
GET /api/posts?per_page=15
Authorization: Bearer YOUR_TOKEN
```

**Query Parameters:**
- `per_page` (optional): Number of posts per page (default: 15)

**Response (200):**
```json
{
  "success": true,
  "data": {
    "posts": [...],
    "pagination": {
      "total": 5,
      "per_page": 15,
      "current_page": 1,
      "last_page": 1
    }
  }
}
```

---

#### Create Post
```
POST /api/posts
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "My New Post",
  "body": "This is the post content with detailed information."
}
```

**Validation Rules:**
- `title`: Required, string, max 255 characters
- `body`: Required, string (no max length)

**Response (201):**
```json
{
  "success": true,
  "data": {
    "post": {
      "id": 6,
      "user_id": 1,
      "title": "My New Post",
      "body": "This is the post content with detailed information.",
      "created_at": "2026-02-05T15:45:00Z",
      "updated_at": "2026-02-05T15:45:00Z"
    }
  }
}
```

---

#### Get Single Post (User's Own)
```
GET /api/posts/{id}
Authorization: Bearer YOUR_TOKEN
```

**Parameters:**
- `id` (required): Post ID

**Response (200):**
```json
{
  "success": true,
  "data": {
    "post": {
      "id": 1,
      "user_id": 1,
      "title": "My Post",
      "body": "Post content",
      "created_at": "2026-02-05T10:30:00Z",
      "updated_at": "2026-02-05T10:30:00Z"
    }
  }
}
```

---

#### Update Post
```
PUT /api/posts/{id}
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json
```

**Parameters:**
- `id` (required): Post ID

**Request Body:**
```json
{
  "title": "Updated Post Title",
  "body": "Updated post content"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "post": {
      "id": 1,
      "user_id": 1,
      "title": "Updated Post Title",
      "body": "Updated post content",
      "updated_at": "2026-02-05T16:00:00Z"
    }
  }
}
```

**Errors:**
- `403`: User doesn't own the post
- `404`: Post not found
- `422`: Validation failed

---

#### Delete Post
```
DELETE /api/posts/{id}
Authorization: Bearer YOUR_TOKEN
```

**Parameters:**
- `id` (required): Post ID

**Response (200):**
```json
{
  "success": true,
  "message": "Post deleted successfully"
}
```

**Errors:**
- `403`: User doesn't own the post
- `404`: Post not found

---

#### Logout
```
POST /api/logout
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

---

### Error Responses

#### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

#### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Post not found"
}
```

#### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "body": ["The body field is required."]
  }
}
```

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test File
```bash
php artisan test tests/Feature/User/PostControllerTest.php
```

### Run Tests with Details
```bash
php artisan test --verbose
```

### Generate Coverage Report
```bash
php artisan test --coverage
```

### Run Failed Tests Only
```bash
php artisan test --failed
```

### Test Structure
```
tests/
├── Feature/
│   ├── Auth/AuthenticationTest.php           # 6 tests
│   ├── User/PostControllerTest.php           # 11 tests
│   ├── Admin/AdminControllerTest.php         # 12 tests
│   └── HomePageTest.php                      # 12 tests
└── Unit/
    └── Models/ModelTest.php                  # 11 tests

Total: 52 Production-Ready Tests ✅
```

### Test Coverage Areas
- ✅ User authentication (registration, login, logout)
- ✅ Post CRUD operations
- ✅ Authorization and access control
- ✅ Validation error handling
- ✅ Model relationships
- ✅ Admin panel functionality
- ✅ Edge cases and security

---

## 🔧 Available Commands

### Development
```bash
# Full development setup
composer setup

# Start development server with watchers
composer dev

# Laravel development server
php artisan serve

# Vite asset watcher
npm run dev
```

### Production
```bash
# Production asset build
npm run build

# Optimize for production
php artisan optimize
```

### Database
```bash
# Run migrations
php artisan migrate

# Reset database
php artisan migrate:refresh

# Reset and seed database
php artisan migrate:fresh --seed
```

### Testing
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter test_name
```

### Cache & Optimization
```bash
# Clear all caches
php artisan optimize:clear

# Create cache files
php artisan optimize
```

---

## 📚 Key Concepts Demonstrated

### 1. **Service Layer Architecture**
The `PostService` class encapsulates all post-related business logic, promoting code reuse and maintainability.

### 2. **Repository Pattern**
Models act as repositories for database access, leveraging Eloquent ORM.

### 3. **Authorization Policies**
`PostPolicy` implements fine-grained access control for post operations.

### 4. **RESTful Design**
API follows RESTful conventions with proper HTTP methods and status codes.

### 5. **Token Authentication**
Laravel Sanctum provides secure, stateless API authentication.

### 6. **Middleware**
Custom middleware handles authorization and authentication checks.

### 7. **Testing Best Practices**
Comprehensive test suite covering happy paths, edge cases, and error scenarios.

### 8. **Database Relationships**
One-to-many relationships between users and posts with cascade deletion.

---

## 🌟 Learning Outcomes

By studying this project, you'll learn:

✅ How to structure a Laravel application  
✅ Building REST APIs with proper conventions  
✅ Implementing authentication and authorization  
✅ Service layer and business logic separation  
✅ Comprehensive testing strategies  
✅ Error handling and validation  
✅ Database migrations and relationships  
✅ Frontend-backend integration  

---

## 📝 Database Schema

### Users Table
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role ENUM('user', 'admin'),
  email_verified_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Posts Table
```sql
CREATE TABLE posts (
  id BIGINT PRIMARY KEY,
  user_id BIGINT FOREIGN KEY,
  title VARCHAR(255),
  body LONGTEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Personal Access Tokens Table
```sql
CREATE TABLE personal_access_tokens (
  id BIGINT PRIMARY KEY,
  tokenable_id BIGINT,
  tokenable_type VARCHAR(255),
  name VARCHAR(255),
  token VARCHAR(64) UNIQUE,
  abilities TEXT,
  last_used_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 🤝 Contributing

This is a learning project. Feel free to:
- Fork the repository
- Create feature branches
- Submit pull requests with improvements
- Report issues

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Your Name**  
Full-Stack Developer | Laravel Enthusiast

---

## 🎯 Future Enhancements

- [ ] Comment system for posts
- [ ] Like/favorite functionality
- [ ] User profile pages
- [ ] Post search and filtering
- [ ] Email notifications
- [ ] Rate limiting improvements
- [ ] GraphQL API alternative
- [ ] WebSocket real-time updates

---

## 📞 Support

For questions or issues:
1. Check existing issues on GitHub
2. Review the testing documentation
3. Examine the API documentation above
4. Review code comments and docblocks

---

<div align="center">

### ⭐ If you find this project helpful, please give it a star!

Built with ❤️ for learning and growth

</div>
