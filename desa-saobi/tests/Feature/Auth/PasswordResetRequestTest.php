<?php

namespace Tests\Feature\Auth;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $this->get('/forgot-password')->assertStatus(200);
    }

    public function test_warga_can_submit_reset_request_with_nik_and_new_password(): void
    {
        $user = User::factory()->create(['nik' => '3529012345670001', 'role' => 'warga']);

        $response = $this->post('/forgot-password', [
            'nik' => '3529012345670001',
            'password' => 'sandibaru123',
            'password_confirmation' => 'sandibaru123',
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'status' => 'pending',
            'password_plain' => 'sandibaru123',
        ]);
    }

    public function test_password_confirmation_must_match(): void
    {
        User::factory()->create(['nik' => '3529012345670004', 'role' => 'warga']);

        $this->post('/forgot-password', [
            'nik' => '3529012345670004',
            'password' => 'sandibaru123',
            'password_confirmation' => 'beda123',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseCount('password_reset_requests', 0);
    }

    public function test_unknown_nik_is_rejected(): void
    {
        $response = $this->post('/forgot-password', [
            'nik' => '0000000000000000',
            'password' => 'sandibaru123',
            'password_confirmation' => 'sandibaru123',
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertDatabaseCount('password_reset_requests', 0);
    }

    public function test_admin_approval_applies_the_requested_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $warga = User::factory()->create([
            'nik' => '3529012345670002',
            'role' => 'warga',
            'password' => Hash::make('lama123'),
        ]);

        $request = PasswordResetRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'status' => 'pending',
            'password_plain' => 'ajuanwarga123',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.reset-sandi.approve', $request));

        $response->assertSessionHas('success');
        $this->assertSame('approved', $request->fresh()->status);
        $this->assertTrue(Hash::check('ajuanwarga123', $warga->fresh()->password));
        $this->assertSame('ajuanwarga123', $warga->fresh()->password_plain);
    }

    public function test_admin_can_override_with_a_different_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $warga = User::factory()->create(['nik' => '3529012345670005', 'role' => 'warga']);

        $request = PasswordResetRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'status' => 'pending',
            'password_plain' => 'ajuanwarga123',
        ]);

        $this->actingAs($admin)->post(route('admin.reset-sandi.approve', $request), [
            'password' => 'sandibaruadmin',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('sandibaruadmin', $warga->fresh()->password));
    }

    public function test_reject_requires_reason(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $warga = User::factory()->create(['nik' => '3529012345670003', 'role' => 'warga']);

        $request = PasswordResetRequest::create([
            'user_id' => $warga->id,
            'nik' => $warga->nik,
            'status' => 'pending',
            'password_plain' => 'ajuanwarga123',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.reset-sandi.reject', $request), [])
            ->assertSessionHasErrors('catatan');

        $this->assertSame('pending', $request->fresh()->status);
    }
}
