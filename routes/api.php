<?php

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
    
    // Return fresh user data from database
    return response()->json($user->fresh());
})->middleware('auth');

// Notification routes (authenticated)
Route::middleware('auth')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/read/all', [NotificationController::class, 'destroyAllRead']);
});

// Check application route (authenticated)
Route::get('/applications/check/{division}', [ApplicationController::class, 'checkExisting'])->middleware('auth');
Route::get('/applications/{application}/status', [ApplicationController::class, 'getStatus'])->middleware('auth');

// Public API routes
Route::get('/divisions', [DivisionController::class, 'index']);
Route::get('/divisions/{division}', [DivisionController::class, 'show']);
