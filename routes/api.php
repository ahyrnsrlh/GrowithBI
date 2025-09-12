<?php

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\ApplicationController;
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

// Check application route (authenticated)
Route::get('/applications/check/{division}', [ApplicationController::class, 'checkExisting'])->middleware('auth');
Route::get('/applications/{application}/status', [ApplicationController::class, 'getStatus'])->middleware('auth');

// Public API routes
Route::get('/divisions', [DivisionController::class, 'index']);
Route::get('/divisions/{division}', [DivisionController::class, 'show']);
