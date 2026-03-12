<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Security Tests for RBAC and IDOR Protection
 * 
 * These tests verify that:
 * 1. Role-based access control is enforced
 * 2. IDOR attacks are prevented
 * 3. 2FA is required for admin/pembimbing
 */
class SecurityAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that peserta cannot access admin dashboard
     */
    public function test_peserta_cannot_access_admin_dashboard(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($peserta)->get('/admin/dashboard');

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that admin can access admin dashboard (with 2FA)
     */
    public function test_admin_needs_2fa_for_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Without 2FA verification
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        // Should redirect to 2FA challenge
        $response->assertRedirect(route('two-factor.challenge'));
    }

    /**
     * Test that admin with 2FA can access admin dashboard
     */
    public function test_admin_with_2fa_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Simulate 2FA verification
        $response = $this->actingAs($admin)
            ->withSession(['two_factor_verified' => true])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that pembimbing can access admin dashboard (with 2FA)
     */
    public function test_pembimbing_with_2fa_can_access_admin_dashboard(): void
    {
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);

        // Simulate 2FA verification
        $response = $this->actingAs($pembimbing)
            ->withSession(['two_factor_verified' => true])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test IDOR protection - user cannot access other user's resources
     */
    public function test_user_cannot_access_other_users_logbook(): void
    {
        $user1 = User::factory()->create(['role' => 'peserta']);
        $user2 = User::factory()->create(['role' => 'peserta']);

        // Create a logbook for user2
        $logbook = \App\Models\Logbook::factory()->create(['user_id' => $user2->id]);

        // User1 tries to access user2's logbook
        $response = $this->actingAs($user1)->get("/profile/logbooks/{$logbook->id}");

        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that user can access their own resources
     */
    public function test_user_can_access_own_logbook(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);

        // Create a logbook for this user
        $logbook = \App\Models\Logbook::factory()->create(['user_id' => $user->id]);

        // User accesses their own logbook
        $response = $this->actingAs($user)->get("/profile/logbooks/{$logbook->id}");

        $response->assertStatus(200);
    }

    /**
     * Test that admin can access all resources
     */
    public function test_admin_can_access_all_user_resources(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'peserta']);

        // Create a logbook for regular user
        $logbook = \App\Models\Logbook::factory()->create(['user_id' => $user->id]);

        // Admin accesses user's logbook (should be allowed)
        $response = $this->actingAs($admin)->get("/profile/logbooks/{$logbook->id}");

        $response->assertStatus(200);
    }

    /**
     * Test rate limiting on sensitive operations
     */
    public function test_rate_limiting_on_profile_photo_upload(): void
    {
        $user = User::factory()->create();

        // Try to upload photo 6 times quickly (limit is 5 per minute)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->actingAs($user)->post('/profile/upload-photo', [
                'photo' => 'test.jpg',
            ]);

            if ($i < 5) {
                // First 5 should succeed or fail validation, but not rate limited
                $this->assertNotEquals(429, $response->status());
            } else {
                // 6th request should be rate limited
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }

    /**
     * Test that unauthenticated users are redirected to login
     */
    public function test_unauthenticated_users_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login'));

        $response = $this->get('/peserta/logbooks');
        $response->assertRedirect(route('login'));

        $response = $this->get('/profile');
        $response->assertRedirect(route('login'));
    }

    /**
     * Test dashboard redirects based on role
     */
    public function test_dashboard_redirects_based_on_role(): void
    {
        // Admin
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));

        // Pembimbing
        $pembimbing = User::factory()->create(['role' => 'pembimbing']);
        $response = $this->actingAs($pembimbing)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));

        // Peserta
        $peserta = User::factory()->create(['role' => 'peserta']);
        $response = $this->actingAs($peserta)->get('/dashboard');
        $response->assertRedirect(route('profile.edit'));
    }
}
