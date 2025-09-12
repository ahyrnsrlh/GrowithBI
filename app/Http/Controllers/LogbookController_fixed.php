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
            return redirect()->route('profile.edit')
                ->with('error', 'Anda belum memiliki status magang yang diterima untuk membuat logbook.');
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

    /**
     * Display the specified resource.
     */
    public function show(Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        $logbook->load(['division', 'comments.user', 'reviewer']);

        return Inertia::render('Peserta/Logbooks/Show', [
            'logbook' => $logbook
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return redirect()->route('peserta.logbooks.show', $logbook)
                ->with('error', 'Logbook ini tidak dapat diedit.');
        }

        $logbook->load('division');

        return Inertia::render('Peserta/Logbooks/Edit', [
            'logbook' => $logbook
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return back()->withErrors(['error' => 'Logbook ini tidak dapat diedit.']);
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

        // Check if logbook for this date already exists (excluding current logbook)
        $existingLogbook = Logbook::where('user_id', $user->id)
            ->where('date', $request->date)
            ->where('id', '!=', $logbook->id)
            ->first();
            
        if ($existingLogbook) {
            return back()->withErrors(['date' => 'Logbook untuk tanggal ini sudah ada.']);
        }

        // Handle file uploads
        $attachments = $logbook->attachments ? json_decode($logbook->attachments, true) : [];
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

        $logbook->update([
            'date' => $request->date,
            'title' => $request->title,
            'activities' => $request->activities,
            'learning_points' => $request->learning_points,
            'challenges' => $request->challenges,
            'duration' => $request->duration,
            'status' => $request->status,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null,
        ]);

        $message = $request->status === 'draft' ? 'Logbook berhasil diperbarui sebagai draft.' : 'Logbook berhasil dikirim untuk review.';
        
        return redirect()->route('peserta.logbooks.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        if (!in_array($logbook->status, ['draft', 'revision'])) {
            return back()->withErrors(['error' => 'Logbook ini tidak dapat dihapus.']);
        }

        // Delete attached files if exists
        if ($logbook->attachments) {
            $attachments = json_decode($logbook->attachments, true);
            foreach ($attachments as $attachment) {
                if (isset($attachment['path'])) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
        }

        $logbook->delete();

        return redirect()->route('peserta.logbooks.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Add comment to logbook
     */
    public function addComment(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if ($logbook->user_id !== $user->id) {
            abort(403, 'Unauthorized access to logbook entry.');
        }

        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        LogbookComment::create([
            'logbook_id' => $logbook->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'is_internal' => false
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
