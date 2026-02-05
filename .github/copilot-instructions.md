# AI Coding Agent Instructions - API Demo (Laravel 12)

## Project Overview
A Laravel 12 full-stack application combining a REST API with session-based web authentication. Features user management with role-based access control (admin/user roles), posts system with user relationships, and Vite-driven frontend builds.

## Architecture Patterns

### Authentication & Authorization
- **Session-based web routes** (`routes/web.php`): Uses Laravel Breeze for auth scaffolding
- **Role-based middleware**: Custom `AdminMiddleware` checks `$user->role === 'admin'` before allowing admin routes
- **API auth**: `auth:sanctum` guard for stateless token authentication on `/api/*` routes
- **Key concept**: User model includes `role` column (added via migration `2026_02_03_085558_add_role_to_users_table.php`)

### Database Relationships
- `User` has many `Post` relationships (one-to-many)
- `Post` belongs to `User` (reverse relationship)
- Foreign keys use `cascadeOnDelete()` - deleting a user cascades to their posts

### Route Organization
- **Web routes** (`routes/web.php`): Public welcome, requires user.php and admin.php
- **User routes** (`routes/user.php`): Authenticated user dashboard, posts CRUD, profile management (protected by `auth` middleware)
- **Admin routes** (`routes/admin.php`): Admin dashboard, user management, post management (protected by `auth` + `admin` middleware)
- **API routes** (`routes/api.php`): Token-authenticated endpoints, basic health check at `/test`
- **Auth routes** (`routes/auth.php`): Breeze-provided login/register flows

## Development Workflows

### Critical Commands
```bash
composer setup              # Initial project setup (install, env setup, key gen, migrations)
composer dev              # Run dev server with queue listener + logs + Vite asset watcher
composer test             # Run PHPUnit tests (Unit + Feature suites)
php artisan serve         # Dev server only (port 8000)
npm run dev              # Vite dev server only
npm run build            # Production asset build
```

### Testing
- **Test framework**: PHPUnit with Laravel's testing traits
- **Test location**: `tests/Feature/` and `tests/Unit/` directories
- **Database**: In-memory SQLite for tests (see `phpunit.xml` DB_DATABASE=`:memory:`)
- **Database refresh**: Tests need `RefreshDatabase` trait to reset state between tests
- **Example**: See `ExampleTest.php` for basic HTTP assertion patterns

### Database Management
```bash
php artisan migrate          # Run pending migrations
php artisan migrate:refresh  # Reset and re-run all migrations
php artisan tinker          # REPL for testing models/queries
```

## Project-Specific Conventions

### Code Structure
- **Controllers**: `app/Http/Controllers/` - Keep thin, delegate to models via Eloquent
  - **User controllers** (`app/Http/Controllers/User/`): `UserDashboardController`, `PostController`, `UserProfileController` - handle authenticated user operations
  - **Admin controllers** (`app/Http/Controllers/Admin/`): `DashboardController`, `UserController`, `PostController`, `AdminProfileController` - admin panel operations
- **Models**: `app/Models/` - Include relationships and query scopes here (not in controllers)
- **Policies**: `app/Policies/` - Authorization logic (e.g., `PostPolicy` ensures users can only manage own posts)
- **Middleware**: `app/Http/Middleware/` - Authorization checks like `AdminMiddleware`
- **Requests**: `app/Http/Requests/` - Form validation (FormRequests for controllers)

### Eloquent & Migrations
- **Model casts** (`User.php`): Automatically cast columns - e.g., `'email_verified_at' => 'datetime'`
- **Mass assignment**: Define `$fillable` arrays explicitly (not `$guarded = []`)
- **Migrations**: Timestamps auto-included in models, foreign keys are constrained by default

### Frontend
- **Build tool**: Vite (not Laravel Mix) with `laravel-vite-plugin`
- **CSS framework**: Tailwind CSS + Tailwind Forms plugin
- **JS framework**: Alpine.js for lightweight interactivity
- **Asset entry points**: `resources/js/app.js` and `resources/css/app.css`

## Integration & Dependencies

### Key Dependencies
- `laravel/sanctum` (^4.0) - Token-based API authentication
- `laravel/breeze` (^2.3) - Auth scaffolding with login/register views
- `vite` (^7.0.7) - Frontend asset bundling
- `tailwindcss` (^3.1.0) + Alpine.js - Frontend styling & interactivity

### External Files to Know
- `.env` - Environment config (database, app key, mail, queue settings)
- `config/` - App-wide settings (auth guards, cache, mail, sanctum)
- `storage/` - Logs, uploaded files, framework cache/sessions

## Common Patterns

### Adding a New Protected Route
1. Create migration for any schema changes (`php artisan make:migration`)
2. Add method to controller (`app/Http/Controllers/`)
3. Register in appropriate route file:
   - Web: Wrap in `Route::middleware(['auth'])->group(...)` in `routes/web.php`
   - API: Use `Route::middleware('auth:sanctum')->...` in `routes/api.php`
4. For admin-only routes, add `admin` middleware (which uses `AdminMiddleware`)

### Adding a Model with Relationships
1. Create migration: `php artisan make:model PostComment -m`
2. Define schema in migration, run migration
3. Add relationship methods to model (`public function post() { return $this->belongsTo(Post::class); }`)
4. Add inverse relationship to parent model

### Testing an Endpoint
1. Create test file in `tests/Feature/` or `tests/Unit/`
2. Use `$this->get()`, `$this->post()`, `$this->actingAs($user)` for auth
3. Assert responses: `$response->assertStatus(200)`, `assertJson()`, etc.
4. Tests use in-memory SQLite - no database pollution between tests

## User Features (Authenticated Users)

### User Dashboard
- **Route**: `GET /dashboard`
- **Controller**: `UserDashboardController::index()`
- **View**: `resources/views/user/dashboard.blade.php`
- **Shows**: User name, email, total posts count with links to manage posts

### User Posts CRUD
- **Controller**: `App\Http\Controllers\User\PostController`
- **Resource routes** (RESTful): index, create, store, show, edit, update, destroy
- **Authorization**: `PostPolicy` prevents users from accessing/editing others' posts using `$this->authorize()`
- **Validation**:
  - `title` - required, string, max 255
  - `body` - required, string
- **Views**:
  - `resources/views/user/posts/index.blade.php` - List user's posts with actions
  - `resources/views/user/posts/create.blade.php` - Create form
  - `resources/views/user/posts/edit.blade.php` - Edit form
  - `resources/views/user/posts/show.blade.php` - Display single post with edit/delete buttons

### User Profile Management
- **Controller**: `UserProfileController`
- **Routes**:
  - `GET /profile/edit` → `edit()` (name: `profile.edit`)
  - `PUT /profile` → `update()` (name: `profile.update`)
  - `PUT /profile/password` → `updatePassword()` (name: `profile.password`)
- **Features**:
  - Update name & email (with unique email validation excluding current user)
  - Change password (validates current password, confirms new password)
  - Uses `Hash::check()` for password verification and `Hash::make()` for hashing
- **View**: `resources/views/user/profile/edit.blade.php` - Split into two cards (profile info & password)

### Security Pattern
- **Post Ownership**: `PostPolicy` checks `$user->id === $post->user_id` before allowing operations
- **Authorization Helper**: Use `$this->authorize('action', $model)` in controller methods to prevent unauthorized access
- **Authenticated Routes**: All user routes wrapped in `Route::middleware('auth')` in `routes/user.php`
- **Admins Excluded**: Admin users have `role = 'admin'` but can still use user features (no specific restriction)
