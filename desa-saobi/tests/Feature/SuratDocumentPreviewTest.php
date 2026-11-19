<?php

namespace Tests\Feature;

use App\Models\SuratRequest;
use App\Models\SuratType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SuratDocumentPreviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeSurat(User $owner): SuratRequest
    {
        $type = SuratType::create([
            'nama' => 'Surat Uji',
            'kode' => 'UJI',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
        ]);

        return SuratRequest::create([
            'user_id' => $owner->id,
            'surat_type_id' => $type->id,
            'data_pemohon' => ['nama' => $owner->name],
            'status' => 'pending',
        ]);
    }

    public function test_owner_can_preview_uploaded_document_inline(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('surat-dokumen/ktp.pdf', 'PDFDATA');

        $owner = User::factory()->create(['role' => 'warga']);
        $surat = $this->makeSurat($owner);
        $doc = $surat->documents()->create([
            'nama_file' => 'KTP',
            'path_file' => 'surat-dokumen/ktp.pdf',
            'jenis_dokumen' => 'ktp',
        ]);

        $response = $this->actingAs($owner)
            ->get(route('client.surat.dokumen.lihat', [$surat, $doc]));

        $response->assertOk();
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
    }

    public function test_other_warga_cannot_preview_document(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('surat-dokumen/ktp.pdf', 'PDFDATA');

        $owner = User::factory()->create(['role' => 'warga']);
        $other = User::factory()->create(['role' => 'warga']);
        $surat = $this->makeSurat($owner);
        $doc = $surat->documents()->create([
            'nama_file' => 'KTP',
            'path_file' => 'surat-dokumen/ktp.pdf',
            'jenis_dokumen' => 'ktp',
        ]);

        $this->actingAs($other)
            ->get(route('client.surat.dokumen.lihat', [$surat, $doc]))
            ->assertForbidden();
    }

    public function test_admin_can_preview_document(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('surat-dokumen/ktp.pdf', 'PDFDATA');

        $owner = User::factory()->create(['role' => 'warga']);
        $admin = User::factory()->create(['role' => 'admin']);
        $surat = $this->makeSurat($owner);
        $doc = $surat->documents()->create([
            'nama_file' => 'KTP',
            'path_file' => 'surat-dokumen/ktp.pdf',
            'jenis_dokumen' => 'ktp',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.permohonan.dokumen.lihat', [$surat, $doc]))
            ->assertOk();
    }
}
