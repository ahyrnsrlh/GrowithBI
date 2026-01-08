<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReportReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:report-reminders 
                            {--type=all : Type of reminder (deadline_approaching, overdue, all)}
                            {--days=3 : Days before deadline for approaching reminder}
                            {--dry-run : Run without sending notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send report reminder notifications (deadline approaching, overdue)';

    /**
     * Default internship duration in months
     */
    private const DEFAULT_INTERNSHIP_MONTHS = 3;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');
        $daysBeforeDeadline = (int) $this->option('days');

        $this->info('Starting report reminder notifications...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No notifications will be sent');
        }

        $totalNotifications = 0;

        if ($type === 'all' || $type === 'deadline_approaching') {
            $count = $this->sendDeadlineApproachingReminders($dryRun, $daysBeforeDeadline);
            $totalNotifications += $count;
            $this->info("Deadline approaching reminders: {$count}");
        }

        if ($type === 'all' || $type === 'overdue') {
            $count = $this->sendOverdueReminders($dryRun);
            $totalNotifications += $count;
            $this->info("Overdue reminders: {$count}");
        }

        $this->info("Total notifications sent: {$totalNotifications}");
        
        Log::info('Report reminders sent', [
            'type' => $type,
            'total' => $totalNotifications,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Calculate estimated end date for internship based on acceptance date
     */
    private function calculateEndDate(Application $application): ?Carbon
    {
        // Use reviewed_at (acceptance date) as start reference
        $startDate = $application->reviewed_at ?? $application->created_at;
        
        if (!$startDate) {
            return null;
        }

        // Calculate end date: start + default internship months
        return Carbon::parse($startDate)->addMonths(self::DEFAULT_INTERNSHIP_MONTHS);
    }

    /**
     * Send notifications for reports with deadline approaching (default 3 days)
     * Notify both Participant and Admin
     */
    private function sendDeadlineApproachingReminders(bool $dryRun, int $daysBeforeDeadline): int
    {
        $count = 0;
        $today = Carbon::today();
        $targetDate = $today->copy()->addDays($daysBeforeDeadline);

        // Get accepted applications
        $acceptedApplications = Application::where('status', 'accepted')
            ->whereHas('user')
            ->with('user')
            ->get();

        if ($acceptedApplications->isEmpty()) {
            $this->line('No accepted applications found.');
            return 0;
        }

        // Get all admins
        $admins = User::whereIn('role', ['admin', 'mentor'])->get();

        foreach ($acceptedApplications as $application) {
            $endDate = $this->calculateEndDate($application);
            
            if (!$endDate) {
                continue;
            }

            // Check if deadline is exactly X days away
            if (!$endDate->isSameDay($targetDate)) {
                continue;
            }

            // Check if report already submitted
            $hasReport = Report::where('user_id', $application->user_id)
                ->whereIn('status', ['submitted', 'approved', 'under_review'])
                ->exists();

            if ($hasReport) {
                continue;
            }

            $this->line("- {$application->user->name}: deadline on {$endDate->format('d/m/Y')}");

            if (!$dryRun) {
                // Create report for notification context
                $report = $this->getOrCreateDummyReport($application);

                // Notify the participant
                $application->user->notify(new ReportNotification($report, 'deadline_approaching'));
                $count++;

                // Notify admins
                foreach ($admins as $admin) {
                    $admin->notify(new ReportNotification($report, 'deadline_approaching'));
                    $count++;
                }
            } else {
                $count += 1 + $admins->count();
            }
        }

        if ($count === 0) {
            $this->line('No approaching deadlines found.');
        }

        return $count;
    }

    /**
     * Send notifications for overdue reports (deadline passed, no submission)
     * Notify both Participant and Admin
     */
    private function sendOverdueReminders(bool $dryRun): int
    {
        $count = 0;
        $today = Carbon::today();

        // Get accepted applications
        $acceptedApplications = Application::where('status', 'accepted')
            ->whereHas('user')
            ->with('user')
            ->get();

        if ($acceptedApplications->isEmpty()) {
            $this->line('No accepted applications found.');
            return 0;
        }

        // Get all admins
        $admins = User::whereIn('role', ['admin', 'mentor'])->get();

        foreach ($acceptedApplications as $application) {
            $endDate = $this->calculateEndDate($application);
            
            if (!$endDate || $endDate->gte($today)) {
                continue; // Not overdue yet
            }

            // Check if report already submitted or approved
            $hasReport = Report::where('user_id', $application->user_id)
                ->whereIn('status', ['submitted', 'approved', 'under_review'])
                ->exists();

            if ($hasReport) {
                continue;
            }

            $daysOverdue = (int) $endDate->diffInDays($today);
            $this->line("- {$application->user->name}: {$daysOverdue} days overdue (deadline: {$endDate->format('d/m/Y')})");

            if (!$dryRun) {
                $report = $this->getOrCreateDummyReport($application);

                // Notify the participant
                $application->user->notify(new ReportNotification($report, 'overdue'));
                $count++;

                // Notify admins
                foreach ($admins as $admin) {
                    $admin->notify(new ReportNotification($report, 'overdue'));
                    $count++;
                }
            } else {
                $count += 1 + $admins->count();
            }
        }

        if ($count === 0) {
            $this->line('No overdue reports found.');
        }

        return $count;
    }

    /**
     * Get or create a dummy report for notification context
     */
    private function getOrCreateDummyReport(Application $application): Report
    {
        $report = Report::where('user_id', $application->user_id)
            ->where('application_id', $application->id)
            ->first();

        if (!$report) {
            $report = new Report([
                'user_id' => $application->user_id,
                'application_id' => $application->id,
                'title' => 'Laporan Akhir',
                'status' => 'pending',
            ]);
        }

        $report->setRelation('user', $application->user);

        return $report;
    }
}
