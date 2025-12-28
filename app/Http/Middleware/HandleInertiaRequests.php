<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     * Uses manifest hash for cache busting.
     */
    public function version(Request $request): ?string
    {
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            return md5_file($manifestPath);
        }
        
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'csrf_token' => csrf_token(), // Always inject fresh CSRF token
            'flash' => [
                'registration_success' => session('registration_success'),
                'user_email' => session('user_email'),
                'user_name' => session('user_name'),
                'success' => session('success'),
                'error' => session('error'),
            ],
        ];
    }
}
