<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * VerifyResourceOwnership Middleware
 * 
 * Prevents Insecure Direct Object Reference (IDOR) attacks by verifying
 * that users can only access resources they own.
 * 
 * Usage: 
 * Route::get('/resource/{resource}', ...)->middleware('ownership:resource,user_id');
 * 
 * This will check if the resource's user_id matches the authenticated user's id.
 * Admins and pembimbing are exempt from this check.
 */
class VerifyResourceOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $resourceParam  The route parameter name (e.g., 'application', 'logbook')
     * @param  string  $ownerColumn  The column name that contains the owner ID (default: 'user_id')
     */
    public function handle(Request $request, Closure $next, string $resourceParam, string $ownerColumn = 'user_id'): Response
    {
        $user = Auth::user();

        // Not authenticated - let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Admins and pembimbing can access all resources
        if (in_array($user->role, ['admin', 'pembimbing'])) {
            return $next($request);
        }

        // Get the resource from route parameters
        $resource = $request->route($resourceParam);

        // If resource doesn't exist, let the controller handle 404
        if (!$resource) {
            return $next($request);
        }

        // Check if resource has the owner column
        if (!isset($resource->$ownerColumn)) {
            // Resource doesn't have ownership column, proceed
            return $next($request);
        }

        // Verify ownership
        if ($resource->$ownerColumn !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke resource ini.');
        }

        return $next($request);
    }
}
