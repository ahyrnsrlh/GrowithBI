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
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;

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
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
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

        // Redirect to register success page
        return redirect()->route('register.success')->with([
            'user_email' => $user->email,
            'user_name' => $user->name,
        ]);
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

    /**
     * Display the registration success view for guests.
     */
    public function success(Request $request): Response|RedirectResponse
    {
        $email = $request->session()->get('user_email');
        $name = $request->session()->get('user_name');

        if (!$email) {
            return redirect()->route('register');
        }

        return Inertia::render('Auth/RegisterSuccess', [
            'email' => $email,
            'name' => $name,
        ]);
    }

    /**
     * Resend verification email for unregistered guest who typed their email.
     */
    public function resendGuestVerification(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah terverifikasi. Silakan login.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Link verifikasi baru telah dikirim ke email Anda.',
        ]);
    }

    /**
     * Allow changing email address for unverified guest account.
     */
    public function changeGuestEmail(Request $request): JsonResponse
    {
        $request->validate([
            'old_email' => 'required|email|exists:users,email',
            'new_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->old_email)->first();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang Anda masukkan salah.',
            ], 422);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah terverifikasi. Silakan login.',
            ], 400);
        }

        // Update email
        $user->email = $request->new_email;
        $user->email_verified_at = null;
        $user->save();

        // Send notification to the new email
        $user->sendEmailVerificationNotification();

        // Also update user_email in session so the view is in sync if refreshed
        $request->session()->put('user_email', $user->email);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diubah. Link verifikasi baru telah dikirim.',
            'new_email' => $user->email,
        ]);
    }
}
