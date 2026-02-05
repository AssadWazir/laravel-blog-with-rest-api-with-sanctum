<?php

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // User Posts Resource Routes
    Route::resource('posts', PostController::class);

    // User Profile Routes (additional profile UI at /profile/edit)
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('user.profile.password');
});
