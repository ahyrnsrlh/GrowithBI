<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CaptchaVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    protected CaptchaVerificationService $captchaService;

    public function __construct(CaptchaVerificationService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'recaptchaSiteKey' => $this->captchaService->getSiteKey(),
            'recaptchaEnabled' => $this->captchaService->isEnabled(),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required', 
                'email:rfc,dns', 
                'exists:users,email'
            ],
            'captcha_token' => ['nullable', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem kami.',
        ]);

        // Verify captcha before sending password reset link
        $this->verifyCaptcha($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox dan folder spam.');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Verify reCAPTCHA token for forgot password request.
     * Admin and pembimbing roles are excluded.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function verifyCaptcha(Request $request): void
    {
        // Skip if captcha is not enabled
        if (!$this->captchaService->isEnabled()) {
            return;
        }

        // Check if captcha should be required for this email
        if (!$this->captchaService->shouldRequireForEmail($request->input('email'))) {
            return;
        }

        $result = $this->captchaService->verify(
            $request->input('captcha_token'),
            'forgot_password',
            $request->input('email')
        );

        if (!$result['success']) {
            throw ValidationException::withMessages([
                'email' => 'Permintaan gagal. Silakan coba kembali.',
            ]);
        }
    }
}
