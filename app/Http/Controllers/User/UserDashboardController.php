<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $postCount = $user->posts()->count();

        return view('user.dashboard', [
            'user' => $user,
            'postCount' => $postCount,
        ]);
    }
}
