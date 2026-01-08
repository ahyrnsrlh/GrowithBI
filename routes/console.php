<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks for Notifications
|--------------------------------------------------------------------------
|
| These scheduled tasks send reminder notifications for logbooks and reports.
| Run `php artisan schedule:run` every minute via cron, or use
| `php artisan schedule:work` for local development.
|
| Cron entry for production:
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
*/

// Logbook Reminders - Run daily at 16:00 (4 PM)
// - Remind participants who haven't submitted logbook today
// - Notify admins about logbooks pending review > 3 days
Schedule::command('notifications:logbook-reminders')
    ->dailyAt('16:00')
    ->description('Send logbook reminder notifications')
    ->withoutOverlapping()
    ->runInBackground();

// Report Deadline Reminders - Run daily at 09:00 (9 AM)
// - Remind about reports with deadline approaching (3 days)
// - Notify about overdue reports
Schedule::command('notifications:report-reminders')
    ->dailyAt('09:00')
    ->description('Send report deadline reminder notifications')
    ->withoutOverlapping()
    ->runInBackground();
