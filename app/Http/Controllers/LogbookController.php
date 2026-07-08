<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\LogbookComment;
use App\Models\Application;
use App\Models\User;
use App\Notifications\LogbookNotification;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLogbookRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('profile.edit')
                ->with('error', 'Anda belum memiliki status magang yang diterima untuk mengakses logbook.');
        }

        $query = Logbook::where('user_id', $user->id)
            ->with(['division', 'comments.user', 'reviewer'])
            ->orderBy('date', 'desc');

        // Filter by month if provided
        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereMonth('date', $month->month)
                  ->whereYear('date', $month->year);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logbooks = $query->get();

        // Calculate statistics
        $stats = [
            'total_logbooks' => $logbooks->count(),
            'pending_logbooks' => $logbooks->where('status', 'submitted')->count(),
            'approved_logbooks' => $logbooks->where('status', 'approved')->count(),
            'revision_logbooks' => $logbooks->where('status', 'revision')->count()
        ];

        return Inertia::render('Profile/Logbooks/Index', [
            'logbooks' => $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title,
                    'date' => $logbook->date,
                    'description' => $logbook->activities, // Map activities to description for Vue component
                    'activities' => $logbook->activities,
                    'learning_points' => $logbook->learning_points,
                    'challenges' => $logbook->challenges,
                    'duration' => $logbook->duration,
                    'hours' => $logbook->duration, // Map duration to hours for Vue component
                    'status' => $logbook->status,
                    'attachments' => $logbook->attachments ? json_decode($logbook->attachments, true) : [],
                    'comments' => $logbook->comments, // Include full comments relation
                    'comments_count' => $logbook->comments->count(),
                    'division' => $logbook->division, // Include division data
                    'created_at' => $logbook->created_at,
                    'updated_at' => $logbook->updated_at
                ];
            }),
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('profile.edit')
                ->with('error', 'Anda belum memiliki status magang yang diterima.');
        }

        return Inertia::render('Peserta/Logbooks/Create', [
            'division' => $acceptedApplication->division
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->first();
            
        if (!$acceptedApplication) {
            return back()->withErrors(['error' => 'Anda belum memiliki status magang yang diterima.']);
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'title' => 'required|string|max:255',
            'activities' => 'required|string|min:10',
            'duration' => 'required|numeric|min:0|max:24',
            'learning_points' => 'nullable|string',
            'challenges' => 'nullable|string',
            'status' => 'required|in:draft,submitted',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,gif'
        ]);

        // Check if logbook for this date already exists
        $existingLogbook = Logbook::where('user_id', $user->id)
            ->where('date', $request->date)
            ->first();
            
        if ($existingLogbook) {
            return back()->withErrors(['date' => 'Logbook untuk tanggal ini sudah ada.']);
        }

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('logbooks', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $logbook = Logbook::create([
            'user_id' => $user->id,
            'division_id' => $acceptedApplication->division_id,
            'date' => $request->date,
            'time_in' => $request->time_in ?? '08:00:00', // Default time_in if not provided
            'title' => $request->title,
            'activities' => $request->activities,
            'learning_points' => $request->learning_points,
            'challenges' => $request->challenges,
            'duration' => $request->duration,
            'status' => $request->status,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
        ]);

        // Send notification to user when logbook is submitted
        if ($request->status === 'submitted') {
            // Notify the participant that their logbook was submitted
            $user->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'user'));
            
            // Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                /** @var User $admin */
                $admin->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'admin'));
            }
            
            // Also notify mentors in the same division
            $mentors = User::where('role', 'mentor')
                ->where('division_id', $logbook->division_id)
                ->get();
            foreach ($mentors as $mentor) {
                /** @var User $mentor */
                $mentor->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'mentor'));
            }
        }

        $message = $request->status === 'draft' ? 'Logbook berhasil disimpan sebagai draft.' : 'Logbook berhasil dikirim untuk review.';
        
        return redirect()->route('profile.edit')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Logbook $logbook)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }
        
        // Check if user owns this logbook
        if (!in_array($user->role, ['admin', 'pembimbing']) && $logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        $logbook->load(['division', 'comments.user', 'reviewer', 'reviewedBy']);

        $logbookData = [
            'id' => $logbook->id,
            'title' => $logbook->title,
            'date' => $logbook->date,
            'activities' => $logbook->activities,
            'learning_points' => $logbook->learning_points,
            'challenges' => $logbook->challenges,
            'duration' => $logbook->duration,
            'status' => $logbook->status,
            'mentor_feedback' => $logbook->mentor_feedback,
            'reviewed_at' => $logbook->reviewed_at,
            'reviewed_by' => $logbook->reviewed_by,
            'attachments' => $logbook->attachments ? json_decode($logbook->attachments, true) : [],
            'created_at' => $logbook->created_at,
            'updated_at' => $logbook->updated_at,
            'division' => $logbook->division ? [
                'id' => $logbook->division->id,
                'name' => $logbook->division->name
            ] : null,
            'comments' => $logbook->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'role' => $comment->user->role
                    ]
                ];
            }),
            'reviewer' => $logbook->reviewer ? [
                'id' => $logbook->reviewer->id,
                'name' => $logbook->reviewer->name
            ] : null
        ];

        if ($request->wantsJson()) {
            return response()->json(['logbook' => $logbookData]);
        }

        return Inertia::render('Profile/Logbooks/Show', [
            'logbook' => $logbookData
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if (!in_array($user->role, ['admin', 'pembimbing']) && $logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        // Check if logbook can be edited
        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('peserta.logbooks.index')
                ->with('error', 'Logbook yang sudah disubmit atau disetujui tidak dapat diedit.');
        }

        return Inertia::render('Profile/Logbooks/Edit', [
            'logbook' => [
                'id' => $logbook->id,
                'title' => $logbook->title,
                'date' => $logbook->date,
                'activities' => $logbook->activities,
                'learning_points' => $logbook->learning_points,
                'challenges' => $logbook->challenges,
                'duration' => $logbook->duration,
                'status' => $logbook->status,
                'attachments' => $logbook->attachments ? json_decode($logbook->attachments, true) : []
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogbookRequest $request, Logbook $logbook)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        $oldStatus = $logbook->status;

        $existingAttachments = $logbook->attachments ? json_decode($logbook->attachments, true) : [];
        $removedFiles = $request->input('removed_files', []);
        
        // Filter out removed files
        $attachmentsToKeep = [];
        $filesToDelete = [];
        
        foreach ($existingAttachments as $attachment) {
            if (in_array($attachment['path'], $removedFiles)) {
                $filesToDelete[] = $attachment['path'];
            } else {
                $attachmentsToKeep[] = $attachment;
            }
        }

        $newAttachments = [];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Handle file uploads first
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('logbooks', $filename, 'public');
                    $newAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ];
                }
            }

            // Merge remaining attachments and new uploads
            $allAttachments = array_merge($attachmentsToKeep, $newAttachments);

            $logbook->update([
                'title' => $request->title,
                'date' => $request->date,
                'time_in' => $request->time_in ?? $logbook->time_in ?? '08:00:00',
                'activities' => $request->activities,
                'learning_points' => $request->learning_points,
                'challenges' => $request->challenges,
                'duration' => $request->duration,
                'status' => $request->status,
                'attachments' => !empty($allAttachments) ? json_encode($allAttachments) : null,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // After successful commit, delete the files queued for removal
            foreach ($filesToDelete as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            // Delete newly uploaded files since database update failed
            foreach ($newAttachments as $newAttachment) {
                if (Storage::disk('public')->exists($newAttachment['path'])) {
                    Storage::disk('public')->delete($newAttachment['path']);
                }
            }
            throw $e;
        }

        // Send notification to user when logbook transitions to 'submitted'
        if ($request->status === 'submitted' && $oldStatus !== 'submitted') {
            // Notify the participant that their logbook was submitted
            $user->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'user'));
            
            // Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                /** @var User $admin */
                $admin->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'admin'));
            }
            
            // Also notify mentors in the same division
            $mentors = User::where('role', 'mentor')
                ->where('division_id', $logbook->division_id)
                ->get();
            foreach ($mentors as $mentor) {
                /** @var User $mentor */
                $mentor->notify(new LogbookNotification($logbook, 'submitted', $user->id, 'mentor'));
            }
        }

        $message = $request->status === 'draft' ? 'Logbook berhasil diperbarui sebagai draft.' : 'Logbook berhasil diperbarui dan dikirim untuk review.';
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if (!in_array($user->role, ['admin', 'pembimbing']) && $logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        // Only allow deletion of non-approved logbooks
        if (!in_array($logbook->status, ['draft', 'submitted', 'revision'])) {
            return back()->with('error', 'Hanya logbook yang belum disetujui yang dapat dihapus.');
        }

        $attachmentsToDelete = [];
        if ($logbook->attachments) {
            $attachmentsToDelete = json_decode($logbook->attachments, true);
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Delete associated comments first to avoid foreign key constraint violations
            $logbook->comments()->delete();

            // Delete logbook record
            $logbook->delete();

            \Illuminate\Support\Facades\DB::commit();

            // Delete associated files from storage only after transaction commits successfully
            foreach ($attachmentsToDelete as $attachment) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attachment['path'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment['path']);
                }
            }

            return back()->with('success', 'Logbook berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Logbook deletion error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus logbook.');
        }
    }

    /**
     * Add comment to logbook
     */
    public function addComment(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if (!in_array($user->role, ['admin', 'pembimbing']) && $logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        LogbookComment::create([
            'logbook_id' => $logbook->id,
            'user_id' => $user->id,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Calculate required days for internship
     */
    private function calculateRequiredDays(Application $application): int
    {
        // Get division duration or default to 60 days
        $startDate = $application->division->start_date ?? now();
        $endDate = $application->division->end_date ?? now()->addDays(60);
        
        // Calculate working days (excluding weekends)
        $workingDays = 0;
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $workingDays++;
            }
            $current->addDay();
        }
        
        return $workingDays;
    }
}
