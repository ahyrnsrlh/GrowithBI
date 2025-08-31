<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\LogbookComment;
use App\Models\Application;
use Illuminate\Http\Request;
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
        $user = Auth::user();
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
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
                    'activities' => $logbook->activities,
                    'duration' => $logbook->duration,
                    'status' => $logbook->status,
                    'attachments' => $logbook->attachments ? json_decode($logbook->attachments, true) : [],
                    'comments_count' => $logbook->comments->count(),
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
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('peserta.logbooks.index')
                ->with('error', 'Anda belum memiliki status magang yang diterima.');
        }

        return Inertia::render('Profile/Logbooks/Create', [
            'division' => $acceptedApplication->division
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
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
            'application_id' => $acceptedApplication->id,
            'division_id' => $acceptedApplication->division_id,
            'date' => $request->date,
            'title' => $request->title,
            'activity' => $request->activities,
            'learning_outcome' => $request->learning_points,
            'duration' => $request->duration,
            'status' => $request->status,
        ]);

        $message = $request->status === 'draft' ? 'Logbook berhasil disimpan sebagai draft.' : 'Logbook berhasil dikirim untuk review.';
        
        return redirect()->route('peserta.logbooks.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        $logbook->load(['division', 'comments.user', 'reviewer', 'reviewedBy']);

        return Inertia::render('Profile/Logbooks/Show', [
            'logbook' => [
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
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if ($logbook->user_id !== $user->id) {
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
    public function update(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        // Check if logbook can be edited
        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('peserta.logbooks.index')
                ->with('error', 'Logbook yang sudah disubmit atau disetujui tidak dapat diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'activities' => 'required|string',
            'learning_points' => 'nullable|string',
            'challenges' => 'nullable|string',
            'duration' => 'required|numeric|min:0.5|max:12',
            'status' => 'required|in:draft,submitted',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('logbooks', $filename, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize()
                ];
            }
        }

        // Merge with existing attachments if any
        $existingAttachments = $logbook->attachments ? json_decode($logbook->attachments, true) : [];
        $allAttachments = array_merge($existingAttachments, $attachments);

        $logbook->update([
            'title' => $request->title,
            'date' => $request->date,
            'activity' => $request->activities,
            'learning_outcome' => $request->learning_points,
            'duration' => $request->duration,
            'status' => $request->status,
        ]);

        $message = $request->status === 'draft' ? 'Logbook berhasil diperbarui sebagai draft.' : 'Logbook berhasil diperbarui dan dikirim untuk review.';
        
        return redirect()->route('peserta.logbooks.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        // Only allow deletion of draft logbooks
        if ($logbook->status !== 'draft') {
            return redirect()->route('peserta.logbooks.index')
                ->with('error', 'Hanya logbook dengan status draft yang dapat dihapus.');
        }

        // Delete associated files
        if ($logbook->attachments) {
            $attachments = json_decode($logbook->attachments, true);
            foreach ($attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $logbook->delete();

        return redirect()->route('peserta.logbooks.index')
            ->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Add comment to logbook
     */
    public function addComment(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        // Check if user owns this logbook
        if ($logbook->user_id !== $user->id) {
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
