<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Application;

class ShareUserAcceptanceStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has accepted application
            $hasAcceptedApplication = Application::where('user_id', $user->id)
                ->where('status', 'diterima')
                ->exists();
            
            // Share this information with Inertia
            Inertia::share([
                'auth.user.has_accepted_application' => $hasAcceptedApplication
            ]);
        }
        
        return $next($request);
    }
}
