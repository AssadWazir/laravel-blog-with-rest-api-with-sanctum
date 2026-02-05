<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $posts = Post::with('user')->latest()->paginate(10);
    return view('welcome', compact('posts'));
});

// Load auth routes first (login, register, profile) so session/CSRF work correctly
require __DIR__.'/auth.php';

// Load user routes
require __DIR__.'/user.php';

// Load admin routes
require __DIR__.'/admin.php';
