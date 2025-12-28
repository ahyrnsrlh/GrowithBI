<?php

namespace App\Http\Middleware;

use App\Enums\RegistrationStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Application;

class ShareUserAcceptanceStatus
{
    /**
     * Statuses that indicate accepted application (grants access to logbook, attendance, etc.)
     */
    private const ACCEPTED_STATUSES = [
        'accepted',
        'letter_ready', 
        'diterima', // Legacy status
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has accepted application (any of the accepted statuses)
            $hasAcceptedApplication = Application::where('user_id', $user->id)
                ->whereIn('status', self::ACCEPTED_STATUSES)
                ->exists();
            
            // Share this information with Inertia
            Inertia::share([
                'auth.user.has_accepted_application' => $hasAcceptedApplication
            ]);
        }
        
        return $next($request);
    }
}
