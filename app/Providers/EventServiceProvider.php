<?php

namespace App\Providers;

use App\Events\AcceptanceLetterUploaded;
use App\Events\ApplicationStatusChanged;
use App\Events\DocumentsCompleted;
use App\Events\NewApplicationSubmitted;
use App\Listeners\SendAcceptanceLetterNotification;
use App\Listeners\SendDocumentsCompletedNotification;
use App\Listeners\SendNewApplicationNotification;
use App\Listeners\SendStatusChangeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Laravel default events
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Registration/Application events
        ApplicationStatusChanged::class => [
            SendStatusChangeNotification::class,
        ],

        NewApplicationSubmitted::class => [
            SendNewApplicationNotification::class,
        ],

        DocumentsCompleted::class => [
            SendDocumentsCompletedNotification::class,
        ],

        AcceptanceLetterUploaded::class => [
            SendAcceptanceLetterNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
