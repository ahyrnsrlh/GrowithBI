<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Logbook;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!$user->hasRole(['admin', 'mentor'])) {
            abort(403, 'Unauthorized access to reports.');
        }

        $divisions = $user->hasRole('admin') 
            ? Division::with('applications')->get()
            : Division::where('id', $user->division_id)->with('applications')->get();

        // Date range filters
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        
        $stats = $this->generateReportStats($dateFrom, $dateTo, $user);

        return Inertia::render('Reports/Index', [
            'divisions' => $divisions,
            'stats' => $stats,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'division_id' => $request->input('division_id')
            ],
            'userRole' => $user->getRoleNames()->first()
        ]);
    }

    /**
     * Generate comprehensive report statistics
     */
    private function generateReportStats($dateFrom, $dateTo, $user)
    {
        $baseApplicationQuery = $user->hasRole('admin') 
            ? Application::query()
            : Application::where('division_id', $user->division_id);

        $baseLogbookQuery = $user->hasRole('admin')
            ? Logbook::query()
            : Logbook::where('division_id', $user->division_id);

        // Application Statistics
        $applicationStats = [
            'total' => (clone $baseApplicationQuery)->count(),
            'accepted' => (clone $baseApplicationQuery)->where('status', 'diterima')->count(),
            'pending' => (clone $baseApplicationQuery)->where('status', 'menunggu')->count(),
            'rejected' => (clone $baseApplicationQuery)->where('status', 'ditolak')->count(),
            'monthly_trend' => $this->getMonthlyApplicationTrend($baseApplicationQuery, $dateFrom, $dateTo)
        ];

        // Logbook Statistics
        $logbookStats = [
            'total_entries' => (clone $baseLogbookQuery)->whereBetween('date', [$dateFrom, $dateTo])->count(),
            'approved_entries' => (clone $baseLogbookQuery)->where('status', 'approved')
                ->whereBetween('date', [$dateFrom, $dateTo])->count(),
            'pending_review' => (clone $baseLogbookQuery)->where('status', 'submitted')
                ->whereBetween('date', [$dateFrom, $dateTo])->count(),
            'completion_rate' => $this->calculateOverallCompletionRate($baseLogbookQuery, $dateFrom, $dateTo),
            'active_participants' => $this->getActiveParticipants($baseLogbookQuery, $dateFrom, $dateTo),
            'daily_submissions' => $this->getDailySubmissionTrend($baseLogbookQuery, $dateFrom, $dateTo)
        ];

        // Division Performance
        $divisionStats = $this->getDivisionPerformance($user, $dateFrom, $dateTo);

        return [
            'applications' => $applicationStats,
            'logbooks' => $logbookStats,
            'divisions' => $divisionStats,
            'summary' => [
                'total_participants' => $applicationStats['accepted'],
                'avg_completion_rate' => $logbookStats['completion_rate'],
                'total_working_hours' => $this->getTotalWorkingHours($baseLogbookQuery, $dateFrom, $dateTo),
                'mentor_workload' => $this->getMentorWorkload($user, $dateFrom, $dateTo)
            ]
        ];
    }

    /**
     * Export applications report
     */
    public function exportApplications(Request $request)
    {
        $user = Auth::user();
        $format = $request->input('format', 'excel'); // excel or pdf
        
        $query = $user->hasRole('admin') 
            ? Application::with(['user', 'division'])
            : Application::with(['user', 'division'])->where('division_id', $user->division_id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('division_id') && $user->hasRole('admin')) {
            $query->where('division_id', $request->division_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        if ($format === 'pdf') {
            return $this->exportApplicationsToPdf($applications, $request);
        }
        
        return $this->exportApplicationsToExcel($applications, $request);
    }

    /**
     * Export logbooks report
     */
    public function exportLogbooks(Request $request)
    {
        $user = Auth::user();
        $format = $request->input('format', 'excel');
        
        $query = $user->hasRole('admin') 
            ? Logbook::with(['user', 'division', 'reviewer'])
            : Logbook::with(['user', 'division', 'reviewer'])->where('division_id', $user->division_id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('division_id') && $user->hasRole('admin')) {
            $query->where('division_id', $request->division_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $logbooks = $query->orderBy('date', 'desc')->get();

        if ($format === 'pdf') {
            return $this->exportLogbooksToPdf($logbooks, $request);
        }
        
        return $this->exportLogbooksToExcel($logbooks, $request);
    }

    /**
     * Export participant progress report
     */
    public function exportParticipantProgress(Request $request)
    {
        $user = Auth::user();
        $participantId = $request->input('participant_id');
        $format = $request->input('format', 'pdf');
        
        $participant = User::findOrFail($participantId);
        $application = Application::where('user_id', $participantId)
            ->where('status', 'diterima')
            ->with('division')
            ->first();
            
        if (!$application) {
            return back()->withErrors(['error' => 'Participant not found or not accepted.']);
        }
        
        // Check permissions
        if ($user->hasRole('mentor') && $application->division_id !== $user->division_id) {
            abort(403);
        }

        $dateFrom = $request->input('date_from', $application->created_at->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        
        $logbooks = Logbook::where('user_id', $participantId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->with(['division', 'comments.user'])
            ->orderBy('date', 'asc')
            ->get();

        $progressData = [
            'participant' => $participant,
            'application' => $application,
            'logbooks' => $logbooks,
            'stats' => [
                'total_days' => $logbooks->count(),
                'approved_days' => $logbooks->where('status', 'approved')->count(),
                'total_hours' => $logbooks->sum(function($logbook) {
                    return ($logbook->duration_hours ?? 0) + (($logbook->duration_minutes ?? 0) / 60);
                }),
                'completion_rate' => $logbooks->count() > 0 
                    ? ($logbooks->where('status', 'approved')->count() / $logbooks->count()) * 100 
                    : 0,
                'avg_daily_hours' => $logbooks->count() > 0 
                    ? $logbooks->sum(function($logbook) {
                        return ($logbook->duration_hours ?? 0) + (($logbook->duration_minutes ?? 0) / 60);
                    }) / $logbooks->count()
                    : 0
            ],
            'date_range' => ['from' => $dateFrom, 'to' => $dateTo]
        ];

        if ($format === 'pdf') {
            return $this->exportProgressToPdf($progressData);
        }
        
        return $this->exportProgressToExcel($progressData);
    }

    /**
     * Generate automated weekly report
     */
    public function generateWeeklyReport(Request $request)
    {
        $user = Auth::user();
        $weekStart = $request->input('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekEnd = Carbon::parse($weekStart)->endOfWeek()->format('Y-m-d');
        
        $reportData = [
            'week_period' => ['start' => $weekStart, 'end' => $weekEnd],
            'generated_at' => now(),
            'generated_by' => $user,
            'stats' => $this->generateReportStats($weekStart, $weekEnd, $user),
            'highlights' => $this->generateWeeklyHighlights($weekStart, $weekEnd, $user),
            'recommendations' => $this->generateRecommendations($weekStart, $weekEnd, $user)
        ];

        // For now, return PDF. In production, you might want to email this
        return $this->exportWeeklyReportToPdf($reportData);
    }

    /**
     * Get monthly application trend
     */
    private function getMonthlyApplicationTrend($baseQuery, $dateFrom, $dateTo)
    {
        return (clone $baseQuery)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::parse($item->month . '-01')->format('M Y'),
                    'count' => $item->count
                ];
            });
    }

    /**
     * Calculate overall completion rate
     */
    private function calculateOverallCompletionRate($baseQuery, $dateFrom, $dateTo)
    {
        $total = (clone $baseQuery)->whereBetween('date', [$dateFrom, $dateTo])->count();
        $approved = (clone $baseQuery)->where('status', 'approved')
            ->whereBetween('date', [$dateFrom, $dateTo])->count();
        
        return $total > 0 ? round(($approved / $total) * 100, 2) : 0;
    }

    /**
     * Get active participants
     */
    private function getActiveParticipants($baseQuery, $dateFrom, $dateTo)
    {
        return (clone $baseQuery)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->distinct('user_id')
            ->count();
    }

    /**
     * Get daily submission trend
     */
    private function getDailySubmissionTrend($baseQuery, $dateFrom, $dateTo)
    {
        return (clone $baseQuery)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw('DATE(date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });
    }

    /**
     * Get division performance statistics
     */
    private function getDivisionPerformance($user, $dateFrom, $dateTo)
    {
        $divisionsQuery = $user->hasRole('admin') 
            ? Division::query()
            : Division::where('id', $user->division_id);

        return $divisionsQuery->get()->map(function($division) use ($dateFrom, $dateTo) {
            $participants = Application::where('division_id', $division->id)
                ->where('status', 'diterima')
                ->count();
                
            $logbookEntries = Logbook::where('division_id', $division->id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count();
                
            $approvedEntries = Logbook::where('division_id', $division->id)
                ->where('status', 'approved')
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count();

            return [
                'id' => $division->id,
                'name' => $division->name,
                'participants' => $participants,
                'logbook_entries' => $logbookEntries,
                'approved_entries' => $approvedEntries,
                'completion_rate' => $logbookEntries > 0 ? ($approvedEntries / $logbookEntries) * 100 : 0,
                'avg_entries_per_participant' => $participants > 0 ? $logbookEntries / $participants : 0
            ];
        });
    }

    /**
     * Get total working hours
     */
    private function getTotalWorkingHours($baseQuery, $dateFrom, $dateTo)
    {
        $logbooks = (clone $baseQuery)
            ->where('status', 'approved')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        return $logbooks->sum(function($logbook) {
            return ($logbook->duration_hours ?? 0) + (($logbook->duration_minutes ?? 0) / 60);
        });
    }

    /**
     * Get mentor workload
     */
    private function getMentorWorkload($user, $dateFrom, $dateTo)
    {
        if ($user->hasRole('admin')) {
            return Logbook::where('status', 'submitted')
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count();
        }

        return Logbook::where('division_id', $user->division_id)
            ->where('status', 'submitted')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->count();
    }

    // Export helper methods will be implemented in the next iteration
    private function exportApplicationsToPdf($applications, $request) { /* Implementation */ }
    private function exportApplicationsToExcel($applications, $request) { /* Implementation */ }
    private function exportLogbooksToPdf($logbooks, $request) { /* Implementation */ }
    private function exportLogbooksToExcel($logbooks, $request) { /* Implementation */ }
    private function exportProgressToPdf($progressData) { /* Implementation */ }
    private function exportProgressToExcel($progressData) { /* Implementation */ }
    private function exportWeeklyReportToPdf($reportData) { /* Implementation */ }
    private function generateWeeklyHighlights($weekStart, $weekEnd, $user) { /* Implementation */ }
    private function generateRecommendations($weekStart, $weekEnd, $user) { /* Implementation */ }
}
