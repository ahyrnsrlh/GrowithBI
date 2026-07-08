<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\User;
use App\Models\Division;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_withdraw_pending_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'division_id' => $division->id,
            'status' => 'menunggu',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('applications.cancel', $application->id));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Lamaran berhasil dibatalkan. Anda dapat mengajukan lamaran baru.');

        $application->refresh();
        $this->assertEquals('cancelled', $application->status);
        $this->assertNotNull($application->cancelled_at);
        $this->assertEquals('Applicant', $application->cancelled_by);
        $this->assertEquals('Dibatalkan oleh Pelamar', $application->cancellation_reason);
    }

    public function test_user_cannot_withdraw_non_pending_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'division_id' => $division->id,
            'status' => 'in_review',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('applications.cancel', $application->id));

        $response->assertSessionHasErrors(['error']);
        
        $application->refresh();
        $this->assertEquals('in_review', $application->status);
    }

    public function test_user_can_apply_again_after_withdrawing_application(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $division = Division::factory()->create();

        $application = Application::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'division_id' => $division->id,
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => 'Applicant',
        ]);

        // Submit profile check existing API or profile page checks
        $this->actingAs($user);
        
        // Assert ProfileController doesn't find active application
        $response = $this->get('/profile');
        $response->assertOk();
        $response->assertSee('canCreateNewApplication');
    }
}
