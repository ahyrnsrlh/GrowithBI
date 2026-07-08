<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDeleteApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_application_and_cleanup_files_and_reports(): void
    {
        Storage::fake('public');

        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create division
        $division = \App\Models\Division::factory()->create();

        // Create applicant user and application with file paths
        $applicant = User::factory()->create(['role' => 'peserta']);

        $application = Application::create([
            'user_id' => $applicant->id,
            'name' => $applicant->name,
            'email' => $applicant->email,
            'division_id' => $division->id,
            'status' => 'menunggu',
            'cv_path' => 'applications/cv/test-cv.pdf',
            'surat_lamaran_path' => 'applications/letter/test-letter.pdf',
            'transkrip_path' => 'applications/transcript/test-transcript.pdf',
            'ktp_path' => 'applications/ktp/test-ktp.pdf',
        ]);

        // Put fake files
        Storage::disk('public')->put('applications/cv/test-cv.pdf', 'cv');
        Storage::disk('public')->put('applications/letter/test-letter.pdf', 'letter');
        Storage::disk('public')->put('applications/transcript/test-transcript.pdf', 'transcript');
        Storage::disk('public')->put('applications/ktp/test-ktp.pdf', 'ktp');

        // Create a report linked to the application
        $report = Report::create([
            'user_id' => $applicant->id,
            'application_id' => $application->id,
            'title' => 'Test Report',
            'file_path' => 'reports/test-report.pdf',
            'file_name' => 'test-report.pdf',
            'file_size' => 123,
            'file_type' => 'application/pdf',
        ]);

        Storage::disk('public')->put('reports/test-report.pdf', 'report');

        // Perform deletion as admin (with 2FA session)
        $response = $this->actingAs($admin)
            ->withSession(['two_factor_verified' => true])
            ->delete(route('admin.applications.destroy', $application->id));

        $response->assertRedirect(route('admin.applications.index'));

        // Application and report should be gone
        $this->assertDatabaseMissing('applications', ['id' => $application->id]);
        $this->assertDatabaseMissing('reports', ['id' => $report->id]);

        // Files should be deleted
        $this->assertFalse(Storage::disk('public')->exists('applications/cv/test-cv.pdf'));
        $this->assertFalse(Storage::disk('public')->exists('applications/letter/test-letter.pdf'));
        $this->assertFalse(Storage::disk('public')->exists('applications/transcript/test-transcript.pdf'));
        $this->assertFalse(Storage::disk('public')->exists('applications/ktp/test-ktp.pdf'));
        $this->assertFalse(Storage::disk('public')->exists('reports/test-report.pdf'));
    }

    public function test_non_admin_cannot_delete_application(): void
    {
        $user = User::factory()->create(['role' => 'pembimbing']);
        $applicant = User::factory()->create(['role' => 'peserta']);
        $division = \App\Models\Division::factory()->create();

        $application = Application::create([
            'user_id' => $applicant->id,
            'name' => $applicant->name,
            'email' => $applicant->email,
            'division_id' => $division->id,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user)->withSession(['two_factor_verified' => true])->delete(route('admin.applications.destroy', $application->id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('applications', ['id' => $application->id]);
    }
}
