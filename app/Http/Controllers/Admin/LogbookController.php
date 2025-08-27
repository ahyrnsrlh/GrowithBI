<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\LogbookComment;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Display a listing of logbooks for admin/mentor review
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Logbook::with(['user.division', 'division', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // If user is mentor, only show logbooks from their division
        if ($user->hasRole('mentor')) {
            $query->where('division_id', $user->division_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by division (admin only)
        if ($request->filled('division') && $user->hasRole('admin')) {
            $query->where('division_id', $request->division);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Search by participant name
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $logbooks = $query->get();

        // Get divisions for filter (admin only)
        $divisions = $user->hasRole('admin') 
            ? Division::select('id', 'name')->get() 
            : collect();

        // Statistics
        $baseQuery = $user->hasRole('mentor') 
            ? Logbook::where('division_id', $user->division_id)
            : Logbook::query();

        $stats = [
            'total_logbooks' => (clone $baseQuery)->count(),
            'pending_logbooks' => (clone $baseQuery)->where('status', 'submitted')->count(),
            'approved_logbooks' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected_logbooks' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'revision_logbooks' => (clone $baseQuery)->where('status', 'revision')->count(),
            'average_hours' => round((clone $baseQuery)->avg('duration') ?? 0, 1)
        ];

        return Inertia::render('Admin/Logbooks/Index', [
            'logbooks' => $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title,
                    'date' => $logbook->date,
                    'activities' => $logbook->activities,
                    'duration' => $logbook->duration,
                    'status' => $logbook->status,
                    'attachments' => $logbook->attachments ? json_decode($logbook->attachments, true) : [],
                    'created_at' => $logbook->created_at,
                    'updated_at' => $logbook->updated_at,
                    'user' => [
                        'id' => $logbook->user->id,
                        'name' => $logbook->user->name,
                        'email' => $logbook->user->email,
                        'division' => $logbook->user->division ? [
                            'id' => $logbook->user->division->id,
                            'name' => $logbook->user->division->name
                        ] : null
                    ]
                ];
            }),
            'divisions' => $divisions,
            'stats' => $stats,
            'filters' => $request->only(['status', 'division', 'date_from', 'date_to', 'search']),
            'userRole' => $user->getRoleNames()->first()
        ]);
    }

    /**
     * Display the specified logbook for review
     */
    public function show(Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeReviewedBy($user)) {
            abort(403, 'Unauthorized access to this logbook.');
        }

        $logbook->load([
            'user', 
            'division', 
            'comments.user', 
            'reviewer', 
            'approver'
        ]);

        return Inertia::render('Admin/Logbooks/Show', [
            'logbook' => $logbook
        ]);
    }

    /**
     * Update logbook status (approve/reject/request revision)
     */
    public function updateStatus(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeReviewedBy($user)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,revision',
            'feedback' => 'required|string|min:5'
        ]);

        $success = false;
        $message = '';

        switch ($request->status) {
            case 'approved':
                $success = $logbook->approve($user, $request->feedback);
                $message = 'Logbook berhasil disetujui.';
                break;
            case 'rejected':
                $success = $logbook->reject($user, $request->feedback);
                $message = 'Logbook ditolak.';
                break;
            case 'revision':
                $success = $logbook->requestRevision($user, $request->feedback);
                $message = 'Permintaan revisi telah dikirim.';
                break;
        }

        if ($success) {
            return back()->with('success', $message);
        }

        return back()->withErrors(['error' => 'Gagal memperbarui status logbook.']);
    }

    /**
     * Add comment to logbook (internal/mentor comment)
     */
    public function addComment(Request $request, Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeReviewedBy($user)) {
            abort(403);
        }

        $request->validate([
            'comment' => 'required|string|min:3',
            'type' => 'required|in:comment,feedback,revision_request',
            'is_internal' => 'boolean'
        ]);

        LogbookComment::create([
            'logbook_id' => $logbook->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'type' => $request->type,
            'is_internal' => $request->is_internal ?? false
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Bulk update status for multiple logbooks
     */
    public function bulkUpdate(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'logbook_ids' => 'required|array',
            'logbook_ids.*' => 'exists:logbooks,id',
            'status' => 'required|in:approved,rejected,revision',
            'feedback' => 'required|string|min:5'
        ]);

        $logbooks = Logbook::whereIn('id', $request->logbook_ids)->get();
        $successCount = 0;

        foreach ($logbooks as $logbook) {
            if ($logbook->canBeReviewedBy($user)) {
                switch ($request->status) {
                    case 'approved':
                        if ($logbook->approve($user, $request->feedback)) {
                            $successCount++;
                        }
                        break;
                    case 'rejected':
                        if ($logbook->reject($user, $request->feedback)) {
                            $successCount++;
                        }
                        break;
                    case 'revision':
                        if ($logbook->requestRevision($user, $request->feedback)) {
                            $successCount++;
                        }
                        break;
                }
            }
        }

        return back()->with('success', "Berhasil memperbarui {$successCount} logbook.");
    }

    /**
     * Legacy method for backward compatibility
     */
    public function approve(Logbook $logbook)
    {
        $user = Auth::user();
        
        if (!$logbook->canBeReviewedBy($user)) {
            abort(403);
        }

        if ($logbook->approve($user, 'Disetujui melalui quick approve')) {
            return back()->with('success', 'Logbook berhasil disetujui.');
        }

        return back()->withErrors(['error' => 'Gagal menyetujui logbook.']);
    }
}
