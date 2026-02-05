<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile edit form.
     */
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'admin' => auth()->user(),
        ]);
    }

    /**
     * Update the admin's name and email.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($admin->id),
            ],
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($admin) {
                    if (!Hash::check($value, $admin->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully!');
    }
}
