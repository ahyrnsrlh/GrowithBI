<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'auth' => [
            'user' => Auth::user()
        ],
    ]);
})->name('home');

// Public routes (accessible without authentication)
Route::get('/divisi', [PublicController::class, 'divisions'])->name('public.divisions');
Route::get('/divisi/{division}', [PublicController::class, 'divisionDetail'])->name('public.division.detail');
Route::get('/daftar', [PublicController::class, 'applicationForm'])->name('public.application.form');
Route::post('/daftar', [PublicController::class, 'storeApplication'])->name('public.application.store');
Route::get('/cek-status', [PublicController::class, 'checkStatus'])->name('public.check.status');
Route::post('/cek-status', [PublicController::class, 'searchStatus'])->name('public.search.status');

// Quick register route (requires authentication)
Route::post('/quick-register', [PublicController::class, 'quickRegister'])->name('public.quick.register')->middleware('auth');

// Web-based authentication check routes (for better session handling)
Route::middleware('auth')->group(function () {
    Route::get('/auth/check', function () {
        return response()->json([
            'authenticated' => true,
            'user' => Auth::user()
        ]);
    })->name('auth.check');
    
    Route::get('/auth/user', function () {
        return response()->json(Auth::user());
    })->name('auth.user');
    
    Route::get('/applications/check/{division}', [App\Http\Controllers\Api\ApplicationController::class, 'checkExisting'])->name('applications.check');
    Route::get('/applications/{application}/status', [App\Http\Controllers\Api\ApplicationController::class, 'getStatus'])->name('applications.status');
});

// Authentication routes
require __DIR__.'/auth.php';

// Default dashboard route (will redirect based on role)
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'peserta':
            return redirect()->route('profile.edit');
        default:
            return redirect()->route('profile.edit');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::post('/profile/upload-document', [ProfileController::class, 'uploadDocument'])->name('profile.upload-document');
    Route::post('/profile/create-application', [ProfileController::class, 'createApplication'])->name('profile.create-application');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (require admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Applications Management
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::post('/applications/bulk-update', [ApplicationController::class, 'bulkUpdate'])->name('applications.bulk-update');
    
    // Divisions Management
    Route::resource('divisions', App\Http\Controllers\Admin\DivisionController::class);
    
    // Supervisors Management (now part of admin)
    Route::resource('supervisors', SupervisorController::class);
    
    // Participants Management (now part of admin)
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::put('/participants/{participant}/status', [ParticipantController::class, 'updateStatus'])->name('participants.update-status');
    
    // Logbooks Management (merged from pembimbing)
    Route::get('/logbooks', [\App\Http\Controllers\Admin\LogbookController::class, 'index'])->name('logbooks.index');
    Route::get('/logbooks/{logbook}', [\App\Http\Controllers\Admin\LogbookController::class, 'show'])->name('logbooks.show');
    Route::put('/logbooks/{logbook}/approve', [\App\Http\Controllers\Admin\LogbookController::class, 'approve'])->name('logbooks.approve');
    
    // Reports & Analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});