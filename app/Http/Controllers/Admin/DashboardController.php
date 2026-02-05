<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalPosts' => $totalPosts,
        ]);
    }
}
