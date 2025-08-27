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

        return Inertia::render('Peserta/Logbooks/Index', [
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

        return Inertia::render('Peserta/Logbooks/Create', [
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
            'division_id' => $acceptedApplication->division_id,
            'date' => $request->date,
            'title' => $request->title,
            'activities' => $request->activities,
            'learning_points' => $request->learning_points,
            'challenges' => $request->challenges,
            'duration' => $request->duration,
            'status' => $request->status,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
        ]);

        $message = $request->status === 'draft' ? 'Logbook berhasil disimpan sebagai draft.' : 'Logbook berhasil dikirim untuk review.';
        
        return redirect()->route('peserta.logbooks.index')->with('success', $message);
    }
            'duration_hours' => 'nullable|integer|min:0|max:12',
            'duration_minutes' => 'nullable|integer|min:0|max:59',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
            'status' => 'required|in:draft,submitted'
        ]);

        // Check if logbook entry already exists for this date
        $existingEntry = Logbook::where('user_id', $user->id)
            ->where('date', $request->date)
            ->first();
            
        if ($existingEntry) {
            return back()->withErrors(['date' => 'Logbook untuk tanggal ini sudah ada.']);
        }

        $data = $request->except('attachment');
        $data['user_id'] = $user->id;
        $data['division_id'] = $acceptedApplication->division_id;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logbooks', $filename, 'public');
            $data['attachment_path'] = $path;
        }

        // Set submitted_at if status is submitted
        if ($request->status === 'submitted') {
            $data['submitted_at'] = now();
        }

        $logbook = Logbook::create($data);

        $message = $request->status === 'submitted' 
            ? 'Logbook berhasil disimpan dan dikirim untuk review.'
            : 'Logbook berhasil disimpan sebagai draft.';

        return redirect()->route('logbook.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        $logbook->load(['division', 'comments.user', 'reviewer', 'approver']);

        return Inertia::render('Logbook/Show', [
            'logbook' => $logbook
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeEditedBy($user)) {
            return redirect()->route('logbook.show', $logbook)
                ->with('error', 'Logbook ini tidak dapat diedit.');
        }

        $logbook->load('division');

        return Inertia::render('Logbook/Edit', [
            'logbook' => $logbook
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeEditedBy($user)) {
            return back()->withErrors(['error' => 'Logbook ini tidak dapat diedit.']);
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'activities' => 'required|string|min:10',
            'duration_hours' => 'nullable|integer|min:0|max:12',
            'duration_minutes' => 'nullable|integer|min:0|max:59',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:draft,submitted'
        ]);

        $data = $request->except('attachment');

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($logbook->attachment_path) {
                Storage::disk('public')->delete($logbook->attachment_path);
            }
            
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logbooks', $filename, 'public');
            $data['attachment_path'] = $path;
        }

        // Set submitted_at if status changes to submitted
        if ($request->status === 'submitted' && $logbook->status === 'draft') {
            $data['submitted_at'] = now();
        }

        $logbook->update($data);

        $message = $request->status === 'submitted' 
            ? 'Logbook berhasil diperbarui dan dikirim untuk review.'
            : 'Logbook berhasil diperbarui.';

        return redirect()->route('logbook.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeEditedBy($user)) {
            return back()->withErrors(['error' => 'Logbook ini tidak dapat dihapus.']);
        }

        // Delete attached file if exists
        if ($logbook->attachment_path) {
            Storage::disk('public')->delete($logbook->attachment_path);
        }

        $logbook->delete();

        return redirect()->route('logbook.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Add comment to logbook entry
     */
    public function addComment(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'comment' => 'required|string|min:3',
            'type' => 'required|in:comment,feedback,revision_request'
        ]);

        LogbookComment::create([
            'logbook_id' => $logbook->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'type' => $request->type,
            'is_internal' => false
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
