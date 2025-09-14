<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function index()
    {
        $stats = [
            'total_divisions' => Division::count(),
            'total_applications' => Application::count(),
            'accepted_applications' => Application::where('status', 'diterima')->count(),
        ];

        return Inertia::render('Admin/Profile/Index', [
            'stats' => $stats
        ]);
    }

    /**
     * Update admin profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Update all fields including profile_photo_path
        $user->update(array_merge(
            $request->only(['name', 'email', 'phone', 'position', 'bio']),
            $request->hasFile('profile_photo') ? ['profile_photo_path' => $user->profile_photo_path] : []
        ));

        // Refresh the user data
        $user->refresh();

        return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Get admin activity logs.
     */
    public function activityLogs()
    {
        // Implement activity logging if needed
        return response()->json([
            'logs' => []
        ]);
    }

    /**
     * Update admin preferences.
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'auto_logout' => 'boolean',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $preferences = $user->preferences ?? [];
        
        $preferences = array_merge($preferences, $request->only([
            'email_notifications',
            'auto_logout'
        ]));

        $user->update(['preferences' => $preferences]);

        return redirect()->back()->with('success', 'Preferensi berhasil disimpan.');
    }

    /**
     * Delete admin profile photo.
     */
    public function deletePhoto()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            // Delete photo from storage
            Storage::disk('public')->delete($user->profile_photo_path);
            
            // Remove from database
            $user->update(['profile_photo_path' => null]);
        }

        return redirect()->back()->with('success', 'Foto profile berhasil dihapus.');
    }
}