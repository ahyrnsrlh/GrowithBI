<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleCSRFErrors
{
    /**
     * Handle CSRF token mismatch errors gracefully.
     * For Inertia/AJAX requests, return JSON with fresh token.
     * For regular requests, redirect back with error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Regenerate session token
            $request->session()->regenerateToken();
            
            // For Inertia/AJAX requests, return JSON with fresh token
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Token has been refreshed.',
                    'csrf_token' => csrf_token(),
                    'errors' => [
                        'csrf' => ['Your session has expired. Please try again.']
                    ]
                ], 419);
            }
            
            // For regular web requests, redirect back with error
            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['csrf' => 'Your session has expired. Please try again.']);
        }
    }
}
