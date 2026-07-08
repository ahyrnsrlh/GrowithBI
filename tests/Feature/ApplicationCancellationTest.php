<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ApplicationCancellationTest
 *
 * Tests the Business Action Pattern for application cancellation:
 *   POST /applications/{application}/cancel  [applications.cancel]
 */
class ApplicationCancellationTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Happy Path
    // -------------------------------------------------------------------------

    public function test_applicant_can_cancel_pending_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id'     => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'division_id' => $division->id,
            'status'      => 'menunggu',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('applications.cancel', $application->id));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Lamaran berhasil dibatalkan. Anda dapat mengajukan lamaran baru.');

        $application->refresh();
        $this->assertEquals('cancelled', $application->status);
        $this->assertNotNull($application->cancelled_at);
        $this->assertEquals('Applicant', $application->cancelled_by);
        $this->assertEquals('Dibatalkan oleh Pelamar', $application->cancellation_reason);
    }

    // -------------------------------------------------------------------------
    // Business Rule Enforcement
    // -------------------------------------------------------------------------

    public function test_applicant_cannot_cancel_in_review_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id'     => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'division_id' => $division->id,
            'status'      => 'in_review',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('applications.cancel', $application->id));

        $response->assertSessionHasErrors(['cancellation']);

        $application->refresh();
        $this->assertEquals('in_review', $application->status); // unchanged
    }

    public function test_applicant_cannot_cancel_accepted_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id'     => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'division_id' => $division->id,
            'status'      => 'accepted',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('applications.cancel', $application->id));

        $response->assertSessionHasErrors(['cancellation']);

        $application->refresh();
        $this->assertEquals('accepted', $application->status); // unchanged
    }

    // -------------------------------------------------------------------------
    // Security
    // -------------------------------------------------------------------------

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $owner = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id'     => $owner->id,
            'name'        => $owner->name,
            'email'       => $owner->email,
            'division_id' => $division->id,
            'status'      => 'menunggu',
        ]);

        $response = $this->post(route('applications.cancel', $application->id));

        $response->assertRedirect(route('login'));
    }

    public function test_applicant_cannot_cancel_another_users_application(): void
    {
        $owner  = User::factory()->create(['role' => 'peserta']);
        $other  = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id'     => $owner->id,
            'name'        => $owner->name,
            'email'       => $owner->email,
            'division_id' => $division->id,
            'status'      => 'menunggu',
        ]);

        $response = $this
            ->actingAs($other)
            ->post(route('applications.cancel', $application->id));

        $response->assertStatus(403);

        $application->refresh();
        $this->assertEquals('menunggu', $application->status); // unchanged
    }

    // -------------------------------------------------------------------------
    // Post-cancellation State
    // -------------------------------------------------------------------------

    public function test_applicant_can_submit_new_application_after_cancellation(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        // Cancelled application — should not block a new application
        Application::create([
            'user_id'      => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'division_id'  => $division->id,
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'Applicant',
        ]);

        // Profile page should indicate user CAN create a new application
        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
        $response->assertSee('canCreateNewApplication');
    }
}
