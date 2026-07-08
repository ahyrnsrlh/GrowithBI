<?php

namespace App\Services;

use App\Enums\RegistrationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * ApplicationService
 *
 * Implements the Business Action Pattern for application lifecycle management.
 * Controllers remain thin—all domain rules and state transitions live here.
 *
 * Status-change actions:
 *   cancel() — Applicant cancels a pending (menunggu) application.
 *
 * Each action:
 *   1. Validates preconditions (throws typed exceptions on failure).
 *   2. Delegates the actual transition to Application::transitionTo() which
 *      handles DB transactions, audit fields, and event/notification dispatch.
 */
class ApplicationService
{
    /**
     * Cancel an applicant's pending application.
     *
     * Business Rules:
     *   - Only applications in 'menunggu' (pending) status may be cancelled.
     *   - Cancellation is a status transition, not a delete — history is preserved.
     *   - An applicant may submit a new application after cancellation.
     *
     * @param  Application  $application  The application to cancel.
     * @param  User         $actor        The authenticated user performing the action.
     * @param  string       $reason       Human-readable cancellation reason (optional).
     *
     * @throws \InvalidArgumentException  If the application is not in a cancellable state.
     * @throws \RuntimeException          If the transition fails unexpectedly.
     */
    public function cancel(
        Application $application,
        User $actor,
        string $reason = 'Dibatalkan oleh Pelamar'
    ): void {
        // Guard: only 'menunggu' applications can be cancelled by the applicant
        if ($application->status !== RegistrationStatus::MENUNGGU->value) {
            throw new \InvalidArgumentException(
                'Lamaran tidak dapat dibatalkan. Hanya lamaran dengan status "Menunggu Review" yang dapat dibatalkan.'
            );
        }

        $metadata = [
            'cancelled_by'        => 'Applicant',
            'cancellation_reason' => $reason,
        ];

        $success = $application->transitionTo(
            RegistrationStatus::CANCELLED,
            $actor,
            $metadata
        );

        if (!$success) {
            Log::warning('ApplicationService::cancel — transitionTo returned false', [
                'application_id' => $application->id,
                'actor_id'       => $actor->id,
                'current_status' => $application->status,
            ]);

            throw new \RuntimeException(
                'Gagal membatalkan lamaran. Transisi status tidak diizinkan.'
            );
        }

        Log::info('ApplicationService::cancel — application cancelled by applicant', [
            'application_id' => $application->id,
            'actor_id'       => $actor->id,
        ]);
    }
}
