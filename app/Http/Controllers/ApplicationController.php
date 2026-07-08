<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ApplicationController (User-facing)
 *
 * Thin controller for applicant-initiated actions on their own applications.
 * All business logic is delegated to ApplicationService.
 *
 * Routes:
 *   POST /applications/{application}/cancel  [applications.cancel]
 *     Middleware: auth, ownership:application, throttle:5,1
 */
class ApplicationController extends Controller
{
    public function __construct(private readonly ApplicationService $applicationService)
    {
    }

    /**
     * Cancel a pending internship application (Business Action).
     *
     * The ownership:application middleware already guarantees the authenticated
     * user owns this application. The double-check below is defense-in-depth.
     *
     * Route: POST /applications/{application}/cancel  [applications.cancel]
     */
    public function cancel(Request $request, Application $application): RedirectResponse
    {
        // Defense-in-depth: middleware already verified ownership, but double-check here
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke lamaran ini.');
        }

        try {
            $this->applicationService->cancel($application, Auth::user());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors([
                'cancellation' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            Log::error('ApplicationController::cancel — unexpected error', [
                'application_id' => $application->id,
                'user_id'        => Auth::id(),
                'error'          => $e->getMessage(),
            ]);

            return back()->withErrors([
                'cancellation' => 'Terjadi kesalahan saat membatalkan lamaran. Silakan coba lagi.',
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Lamaran berhasil dibatalkan. Anda dapat mengajukan lamaran baru.');
    }
}
