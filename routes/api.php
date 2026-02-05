<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/test', function () {
    return ['name' => 'Assad', 'role' => 'developer'];
});

// Public routes - Anyone can view all posts and details
Route::get('/public/posts', [PostController::class, 'publicIndex']);
Route::get('/public/posts/{post}', [PostController::class, 'publicShow']);

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - Require Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // User endpoint
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Post routes - Full CRUD for authenticated users
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});