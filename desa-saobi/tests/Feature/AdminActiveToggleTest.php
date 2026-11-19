<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActiveToggleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_surat_type_can_toggle_active_off(): void
    {
        $this->actingAs($this->admin())->post(route('admin.jenis-surat.store'), [
            'nama' => 'Surat Uji',
            'kode' => 'UJI',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
            // is_active sengaja tidak dikirim (checkbox tidak dicentang)
        ])->assertRedirect(route('admin.jenis-surat.index'));

        $this->assertDatabaseHas('surat_types', ['kode' => 'UJI', 'is_active' => false]);
    }

    public function test_surat_type_can_toggle_active_on(): void
    {
        $this->actingAs($this->admin())->post(route('admin.jenis-surat.store'), [
            'nama' => 'Surat Uji',
            'kode' => 'UJI',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
            'is_active' => '1',
        ])->assertRedirect(route('admin.jenis-surat.index'));

        $this->assertDatabaseHas('surat_types', ['kode' => 'UJI', 'is_active' => true]);
    }

    public function test_template_can_toggle_active_off(): void
    {
        $this->actingAs($this->admin())->post(route('admin.template-surat.store'), [
            'nama' => 'Template Uji',
            'konten' => 'Isi {nama}',
            'form_fields_json' => '[]',
            // is_active sengaja tidak dikirim
        ])->assertRedirect(route('admin.template-surat.index'));

        $this->assertDatabaseHas('surat_templates', ['nama' => 'Template Uji', 'is_active' => false]);
    }
}
