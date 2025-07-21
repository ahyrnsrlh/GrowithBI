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
    ]);
});

// Public routes (accessible without authentication)
Route::get('/divisi', [PublicController::class, 'divisions'])->name('public.divisions');
Route::get('/daftar', [PublicController::class, 'applicationForm'])->name('public.application.form');
Route::post('/daftar', [PublicController::class, 'storeApplication'])->name('public.application.store');

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
            return redirect()->route('peserta.dashboard');
        default:
            return redirect()->route('login');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
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

// Peserta Routes (require peserta role)
Route::prefix('peserta')->name('peserta.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    
    // TODO: Add more peserta routes as needed
});
