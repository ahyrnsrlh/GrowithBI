<?php

use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Check application route (authenticated)
Route::get('/applications/check/{division}', [ApplicationController::class, 'checkExisting'])->middleware('auth:sanctum');

// Public API routes
Route::get('/divisions', [DivisionController::class, 'index']);
Route::get('/divisions/{division}', [DivisionController::class, 'show']);
