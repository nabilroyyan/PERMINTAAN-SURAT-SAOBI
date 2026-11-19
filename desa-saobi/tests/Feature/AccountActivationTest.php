<?php

namespace Tests\Feature;

use App\Models\AccountActivationRequest;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountActivationTest extends TestCase
{
    use RefreshDatabase;

    private function buatPenduduk(string $nik = '3529012345679999', string $noKk = '3529012345670000'): Penduduk
    {
        return Penduduk::create([
            'nik' => $nik,
            'no_kk' => $noKk,
            'nama_lengkap' => 'Siti Aminah',
            'jenis_kelamin' => 'Perempuan',
        ]);
    }

    public function test_warga_without_account_can_request_activation(): void
    {
        $this->buatPenduduk();

        $response = $this->post('/aktivasi-akun', [
            'nik' => '3529012345679999',
            'email' => 'siti@example.com',
            'password' => 'sandi123',
            'password_confirmation' => 'sandi123',
        ]);

        $response->assertSessionHas('activation_status');
        $this->assertDatabaseHas('account_activation_requests', [
            'nik' => '3529012345679999',
            'email' => 'siti@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_activation_request_rejected_when_account_already_exists(): void
    {
        $penduduk = $this->buatPenduduk();
        $user = User::factory()->create(['nik' => '3529012345679999', 'role' => 'warga']);
        $penduduk->update(['user_id' => $user->id]);

        $this->post('/aktivasi-akun', [
            'nik' => '3529012345679999',
            'email' => 'baru@example.com',
            'password' => 'sandi123',
            'password_confirmation' => 'sandi123',
        ])->assertSessionHasErrors('nik', null, 'aktivasi');

        $this->assertDatabaseCount('account_activation_requests', 0);
    }

    public function test_unknown_nik_is_rejected(): void
    {
        $this->post('/aktivasi-akun', [
            'nik' => '0000000000000000',
            'email' => 'x@example.com',
            'password' => 'sandi123',
            'password_confirmation' => 'sandi123',
        ])->assertSessionHasErrors('nik', null, 'aktivasi');
    }

    public function test_admin_approval_creates_and_activates_account(): void
    {
        $penduduk = $this->buatPenduduk();
        $admin = User::factory()->create(['role' => 'admin']);

        $request = AccountActivationRequest::create([
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama_lengkap,
            'email' => 'siti@example.com',
            'password_plain' => 'sandi123',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.aktivasi-akun.approve', $request))
            ->assertSessionHas('success');

        $user = User::where('nik', $penduduk->nik)->first();

        $this->assertNotNull($user);
        $this->assertSame('warga', $user->role->value);
        $this->assertSame('siti@example.com', $user->email);
        $this->assertTrue(Hash::check('sandi123', $user->password));
        $this->assertSame($user->id, $penduduk->fresh()->user_id);
        $this->assertSame('approved', $request->fresh()->status);
    }

    public function test_reject_requires_reason(): void
    {
        $penduduk = $this->buatPenduduk();
        $admin = User::factory()->create(['role' => 'admin']);

        $request = AccountActivationRequest::create([
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama_lengkap,
            'email' => 'siti@example.com',
            'password_plain' => 'sandi123',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.aktivasi-akun.reject', $request), [])
            ->assertSessionHasErrors('catatan');

        $this->assertSame('pending', $request->fresh()->status);
    }
}
