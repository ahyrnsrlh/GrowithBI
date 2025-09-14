<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
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
    
    // Application cancellation (available for logged in users)
    Route::delete('/applications/{application}', [App\Http\Controllers\ProfileController::class, 'cancelApplication'])->name('applications.destroy');
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
    
    // Logbook routes for accepted participants
    Route::get('/profile/logbooks', [App\Http\Controllers\LogbookController::class, 'index'])->name('profile.logbooks.index');
    Route::get('/profile/logbooks/create', [App\Http\Controllers\LogbookController::class, 'create'])->name('profile.logbooks.create');
    Route::post('/profile/logbooks', [App\Http\Controllers\LogbookController::class, 'store'])->name('profile.logbooks.store');
    Route::get('/profile/logbooks/{logbook}', [App\Http\Controllers\LogbookController::class, 'show'])->name('profile.logbooks.show');
    Route::get('/profile/logbooks/{logbook}/edit', [App\Http\Controllers\LogbookController::class, 'edit'])->name('profile.logbooks.edit');
    Route::patch('/profile/logbooks/{logbook}', [App\Http\Controllers\LogbookController::class, 'update'])->name('profile.logbooks.update');
    Route::delete('/profile/logbooks/{logbook}', [App\Http\Controllers\LogbookController::class, 'destroy'])->name('profile.logbooks.destroy');
    Route::post('/profile/logbooks/{logbook}/comments', [App\Http\Controllers\LogbookController::class, 'addComment'])->name('profile.logbooks.comments.store');
    
    // Reports routes for accepted participants
    Route::get('/profile/reports', [App\Http\Controllers\Peserta\PesertaReportController::class, 'index'])->name('profile.reports.index');
    Route::get('/profile/reports/create', [App\Http\Controllers\Peserta\PesertaReportController::class, 'create'])->name('profile.reports.create');
    Route::post('/profile/reports', [App\Http\Controllers\Peserta\PesertaReportController::class, 'store'])->name('profile.reports.store');
    Route::get('/profile/reports/{report}', [App\Http\Controllers\Peserta\PesertaReportController::class, 'show'])->name('profile.reports.show');
    Route::get('/profile/reports/{report}/download', [App\Http\Controllers\Peserta\PesertaReportController::class, 'download'])->name('profile.reports.download');
    Route::get('/profile/reports/{report}/edit', [App\Http\Controllers\Peserta\PesertaReportController::class, 'edit'])->name('profile.reports.edit');
    Route::put('/profile/reports/{report}', [App\Http\Controllers\Peserta\PesertaReportController::class, 'update'])->name('profile.reports.update');
    Route::delete('/profile/reports/{report}', [App\Http\Controllers\Peserta\PesertaReportController::class, 'destroy'])->name('profile.reports.destroy');
    
    // Acceptance Letter routes
    Route::post('/applications/{application}/acceptance-letter/upload', [App\Http\Controllers\AcceptanceLetterController::class, 'upload'])->name('acceptance-letter.upload');
    Route::get('/applications/{application}/acceptance-letter/download', [App\Http\Controllers\AcceptanceLetterController::class, 'download'])->name('acceptance-letter.download');
    Route::get('/applications/{application}/acceptance-letter/check', [App\Http\Controllers\AcceptanceLetterController::class, 'check'])->name('acceptance-letter.check');
    
    // Cancel application (global route for applications management)
    Route::delete('/applications/{application}', [ProfileController::class, 'cancelApplication'])->name('applications.cancel');
});

// Peserta Routes (require peserta role)
Route::prefix('peserta')->name('peserta.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::resource('logbooks', App\Http\Controllers\LogbookController::class);
    Route::post('/logbooks/{logbook}/comments', [App\Http\Controllers\LogbookController::class, 'addComment'])->name('logbooks.comments.store');
    
    // Reports management for participants
    Route::resource('reports', App\Http\Controllers\Peserta\PesertaReportController::class);
    
    // Application management for participants
    Route::delete('/applications/{application}', [App\Http\Controllers\ProfileController::class, 'cancelApplication'])->name('applications.cancel');
});

// Logbook routes (accessible by accepted participants only) - Legacy support
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('logbook', App\Http\Controllers\LogbookController::class);
    Route::post('/logbook/{logbook}/comments', [App\Http\Controllers\LogbookController::class, 'addComment'])->name('logbook.comments.store');
});

// Admin Routes (require admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/photo', [\App\Http\Controllers\Admin\ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::get('/profile/activity-logs', [\App\Http\Controllers\Admin\ProfileController::class, 'activityLogs'])->name('profile.activity-logs');
    Route::put('/profile/preferences', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePreferences'])->name('profile.preferences');
    
    // Applications Management
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::post('/applications/bulk-update', [ApplicationController::class, 'bulkUpdate'])->name('applications.bulk-update');
    
    // Acceptance Letter routes for admin
    Route::post('/applications/{application}/upload-acceptance-letter', [App\Http\Controllers\AcceptanceLetterController::class, 'upload'])->name('applications.upload-acceptance-letter');
    
    // Divisions Management
    Route::resource('divisions', App\Http\Controllers\Admin\DivisionController::class);
    
    // Participants Management (now part of admin)
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [ParticipantController::class, 'show'])->name('participants.show');
    Route::put('/participants/{participant}/status', [ParticipantController::class, 'updateStatus'])->name('participants.update-status');
    
    // Logbooks Management (enhanced with review capabilities)
    Route::get('/logbooks', [\App\Http\Controllers\Admin\LogbookController::class, 'index'])->name('logbooks.index');
    Route::get('/logbooks/{logbook}', [\App\Http\Controllers\Admin\LogbookController::class, 'show'])->name('logbooks.show');
    Route::put('/logbooks/{logbook}/status', [\App\Http\Controllers\Admin\LogbookController::class, 'updateStatus'])->name('logbooks.update-status');
    Route::post('/logbooks/{logbook}/comments', [\App\Http\Controllers\Admin\LogbookController::class, 'addComment'])->name('logbooks.comments.store');
    Route::post('/logbooks/bulk-update', [\App\Http\Controllers\Admin\LogbookController::class, 'bulkUpdate'])->name('logbooks.bulk-update');
    Route::put('/logbooks/{logbook}/approve', [\App\Http\Controllers\Admin\LogbookController::class, 'approve'])->name('logbooks.approve');
    Route::get('/logbooks/stats/dashboard', [\App\Http\Controllers\Admin\LogbookController::class, 'getStats'])->name('logbooks.stats');
    Route::get('/logbooks/export', [\App\Http\Controllers\Admin\LogbookController::class, 'exportLogbooks'])->name('logbooks.export');
    
    // Final Reports Management
    Route::get('/final-reports', [\App\Http\Controllers\Admin\FinalReportController::class, 'index'])->name('final-reports.index');
    Route::get('/final-reports/{report}', [\App\Http\Controllers\Admin\FinalReportController::class, 'show'])->name('final-reports.show');
    Route::put('/final-reports/{report}/status', [\App\Http\Controllers\Admin\FinalReportController::class, 'updateStatus'])->name('final-reports.update-status');
    Route::get('/final-reports/{report}/download', [\App\Http\Controllers\Admin\FinalReportController::class, 'download'])->name('final-reports.download');
    Route::post('/final-reports/{report}/feedback', [\App\Http\Controllers\Admin\FinalReportController::class, 'addFeedback'])->name('final-reports.add-feedback');
    
    // Export capabilities for final reports
    Route::get('/final-reports/export/all', [\App\Http\Controllers\Admin\FinalReportController::class, 'exportAll'])->name('final-reports.export.all');
    Route::get('/applications/export', [ApplicationController::class, 'exportApplications'])->name('applications.export');
});