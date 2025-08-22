<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register', [
            'division_id' => $request->get('division_id')
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:peserta,pembimbing',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'peserta', // Default role peserta
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role with division_id if provided
        return $this->redirectBasedOnRole($user, $request->get('division_id'));
    }

    /**
     * Redirect user based on their role after registration
     */
    private function redirectBasedOnRole(User $user, $divisionId = null): RedirectResponse
    {
        $redirect = match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pembimbing' => redirect()->route('profile.edit'),
            'peserta' => redirect()->route('profile.edit'),
            default => redirect()->route('dashboard'),
        };

        // Add division_id parameter if provided
        if ($divisionId) {
            $redirect->with('division_id', $divisionId);
        }

        return $redirect;
    }
}
