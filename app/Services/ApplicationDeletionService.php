<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationDeletionService
{
    /**
     * Permanently delete an application and its related records/files.
     *
     * This method attempts to keep database operations in a transaction
     * and deletes files after the DB transaction completes to avoid
     * leaving dangling DB records if file deletion fails.
     *
     * @param Application $application
     * @param User $performedBy
     * @return void
     */
    public function delete(Application $application, User $performedBy): void
    {
        $applicationId = $application->id;

        // Collect file paths to delete after DB transaction
        $filePaths = [];

        // Application-file columns
        $possiblePaths = [
            $application->cv_path,
            $application->surat_lamaran_path,
            $application->transkrip_path,
            $application->ktp_path,
            $application->acceptance_letter_path,
        ];

        foreach ($possiblePaths as $p) {
            if (!empty($p)) {
                $filePaths[] = $p;
            }
        }

        // Reports and their files
        $reports = Report::where('application_id', $applicationId)->get();
        foreach ($reports as $report) {
            if (!empty($report->file_path)) {
                $filePaths[] = $report->file_path;
            }
        }

        // First, attempt to delete all related files to avoid leaving orphan files.
        $uniquePaths = array_unique($filePaths);
        foreach ($uniquePaths as $path) {
            if (empty($path)) {
                continue;
            }

            try {
                $disk = Storage::disk('public');
                if ($disk->exists($path)) {
                    $deleted = $disk->delete($path);
                    if (!$deleted) {
                        Log::error('Failed to delete application file (delete returned false)', [
                            'application_id' => $applicationId,
                            'file' => $path,
                        ]);
                        throw new \Exception('Failed to delete file: ' . $path);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to delete application file during pre-cleanup', [
                    'application_id' => $applicationId,
                    'file' => $path,
                    'error' => $e->getMessage(),
                ]);

                // If file deletion fails, abort to avoid leaving orphan files
                throw $e;
            }
        }

        // Perform DB deletions inside a transaction
        DB::transaction(function () use ($application, $applicationId) {
            // Delete reports records
            Report::where('application_id', $applicationId)->delete();

            // Delete notifications that reference this application
            DB::table('notifications')->whereJsonContains('data->application_id', $applicationId)->delete();

            // Finally delete the application record
            $application->delete();
        });

        // Audit log
        Log::info('Application deleted', [
            'application_id' => $applicationId,
            'performed_by_id' => $performedBy->id ?? null,
            'performed_by_name' => $performedBy->name ?? null,
        ]);
    }
}
