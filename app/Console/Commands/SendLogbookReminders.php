<?php

namespace App\Console\Commands;

use App\Models\Logbook;
use App\Models\User;
use App\Notifications\LogbookNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLogbookReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:logbook-reminders 
                            {--type=all : Type of reminder (pending_overdue, not_submitted_today, all)}
                            {--dry-run : Run without sending notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send logbook reminder notifications (pending review > 3 days, not submitted today)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');

        $this->info('Starting logbook reminder notifications...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No notifications will be sent');
        }

        $totalNotifications = 0;

        if ($type === 'all' || $type === 'pending_overdue') {
            $count = $this->sendPendingOverdueReminders($dryRun);
            $totalNotifications += $count;
            $this->info("Pending overdue reminders: {$count}");
        }

        if ($type === 'all' || $type === 'not_submitted_today') {
            $count = $this->sendNotSubmittedTodayReminders($dryRun);
            $totalNotifications += $count;
            $this->info("Not submitted today reminders: {$count}");
        }

        $this->info("Total notifications sent: {$totalNotifications}");
        
        Log::info('Logbook reminders sent', [
            'type' => $type,
            'total' => $totalNotifications,
            'dry_run' => $dryRun,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Send notifications for logbooks pending review > 3 days
     * Notify Admin/Supervisor
     */
    private function sendPendingOverdueReminders(bool $dryRun): int
    {
        $count = 0;

        // Get logbooks that have been pending for more than 3 days
        $overdueLogbooks = Logbook::where('status', 'submitted')
            ->where('submitted_at', '<=', Carbon::now()->subDays(3))
            ->whereHas('user') // Only get logbooks with valid users
            ->with('user')
            ->get();

        if ($overdueLogbooks->isEmpty()) {
            $this->line('No overdue pending logbooks found.');
            return 0;
        }

        // Get all admins and mentors
        $admins = User::whereIn('role', ['admin', 'mentor'])->get();

        foreach ($overdueLogbooks as $logbook) {
            if (!$logbook->user) {
                continue; // Skip logbooks without valid users
            }

            $this->line("- Logbook #{$logbook->id} by {$logbook->user->name} (submitted: {$logbook->submitted_at->format('d/m/Y')})");

            if (!$dryRun) {
                foreach ($admins as $admin) {
                    $admin->notify(new LogbookNotification(
                        $logbook,
                        'pending_overdue',
                        null,
                        'admin'
                    ));
                    $count++;
                }
            } else {
                $count += $admins->count();
            }
        }

        return $count;
    }

    /**
     * Send notifications for participants who haven't submitted logbook today
     * Notify the Participant
     */
    private function sendNotSubmittedTodayReminders(bool $dryRun): int
    {
        $count = 0;
        $today = Carbon::today();

        // Get active interns (peserta with accepted applications)
        $activeInterns = User::where('role', 'peserta')
            ->whereHas('applications', function ($query) {
                $query->where('status', 'accepted');
            })
            ->get();

        foreach ($activeInterns as $intern) {
            // Check if they have submitted logbook today
            $hasSubmittedToday = Logbook::where('user_id', $intern->id)
                ->whereDate('date', $today)
                ->exists();

            if (!$hasSubmittedToday) {
                $this->line("- {$intern->name} has not submitted logbook today");

                if (!$dryRun) {
                    // Create a dummy logbook for notification context
                    // In real scenario, we might want to handle this differently
                    $dummyLogbook = new Logbook([
                        'user_id' => $intern->id,
                        'date' => $today,
                        'status' => 'pending',
                    ]);
                    $dummyLogbook->setRelation('user', $intern);

                    $intern->notify(new LogbookNotification(
                        $dummyLogbook,
                        'not_submitted_today',
                        null,
                        'peserta'
                    ));
                }
                $count++;
            }
        }

        return $count;
    }
}
