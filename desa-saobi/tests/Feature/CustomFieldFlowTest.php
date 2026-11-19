<?php

namespace Tests\Feature;

use App\Models\SuratRequest;
use App\Models\SuratTemplate;
use App\Models\SuratType;
use App\Models\User;
use App\Support\SuratTemplateRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomFieldFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_template_stores_warga_input_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.template-surat.store'), [
            'nama' => 'Template Uji',
            'konten' => 'Luas tanah: {luas_tanah}',
            'variabel_tambahan_json' => json_encode([
                ['kode' => 'luas_tanah', 'label' => 'Luas Tanah', 'tipe' => 'text', 'wajib' => true, 'options' => []],
                ['kode' => 'batas_barat', 'label' => 'Batas Barat', 'tipe' => 'text', 'wajib' => true, 'options' => []],
            ]),
            'is_active' => '1',
        ])->assertRedirect(route('admin.template-surat.index'));

        $template = SuratTemplate::where('nama', 'Template Uji')->first();
        $this->assertNotNull($template);
        $this->assertCount(2, $template->formFields());
        $this->assertSame('luas_tanah', $template->formFields()[0]['kode']);
    }

    public function test_jenis_surat_stores_dynamic_persyaratan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.jenis-surat.store'), [
            'nama' => 'Surat Uji',
            'kode' => 'UJI',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
            'persyaratan_json' => json_encode([
                ['nama' => 'Fotokopi KTP', 'wajib' => true],
                ['nama' => 'Fotokopi KK', 'wajib' => false],
                ['nama' => '', 'wajib' => false],
            ]),
        ])->assertRedirect(route('admin.jenis-surat.index'));

        $type = SuratType::where('kode', 'UJI')->first();
        $this->assertNotNull($type);
        $this->assertSame([
            ['nama' => 'Fotokopi KTP', 'wajib' => true],
            ['nama' => 'Fotokopi KK', 'wajib' => false],
        ], $type->persyaratan);
    }

    public function test_warga_input_from_template_is_saved_and_substituted(): void
    {
        $warga = User::factory()->create(['role' => 'warga', 'nik' => '3529012345670009']);

        $template = SuratTemplate::create([
            'nama' => 'Template Tanah',
            'konten' => 'Luas tanah: {luas_tanah}',
            'variabel_tambahan' => [
                ['kode' => 'luas_tanah', 'label' => 'Luas Tanah', 'tipe' => 'text', 'wajib' => true, 'options' => []],
            ],
        ]);

        $type = SuratType::create([
            'nama' => 'Surat Tanah',
            'kode' => 'TNH',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
            'surat_template_id' => $template->id,
        ]);

        // Field warga diambil dari template
        $fields = $type->fresh()->load('template')->effectiveFormFields();
        $this->assertSame('luas_tanah', $fields[0]['kode']);

        $this->actingAs($warga)->post(route('client.surat.store'), [
            'surat_type_id' => $type->id,
            'tambahan' => ['luas_tanah' => '250'],
        ]);

        $surat = SuratRequest::latest('id')->first();
        $this->assertNotNull($surat);
        $this->assertSame('250', $surat->data_pemohon['tambahan']['luas_tanah'] ?? null);

        $vars = SuratTemplateRenderer::variables($surat->load('user', 'suratType'), null);
        $this->assertSame('250', $vars['luas_tanah']);
    }

    public function test_template_preview_substitutes_warga_variables_with_sample(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.template-surat.preview'), [
            'konten' => 'Batas barat: {batas_barat}',
            'judul' => 'Surat Uji',
            'variabel_tambahan_json' => json_encode([
                ['kode' => 'batas_barat', 'label' => 'Batas Barat'],
            ]),
        ]);

        $response->assertOk();
        $response->assertSee('Contoh Batas Barat', false);
    }

    public function test_warga_can_upload_persyaratan_documents(): void
    {
        Storage::fake('public');

        $warga = User::factory()->create(['role' => 'warga', 'nik' => '3529012345670010']);

        $template = SuratTemplate::create(['nama' => 'Template Syarat', 'konten' => 'Isi']);

        $type = SuratType::create([
            'nama' => 'Surat Syarat',
            'kode' => 'SYR',
            'format_nomor' => 'X/{NOMOR}',
            'nomor_terakhir' => 0,
            'panjang_nomor' => 2,
            'surat_template_id' => $template->id,
            'persyaratan' => [
                ['nama' => 'Fotokopi KTP', 'wajib' => true],
                ['nama' => 'Fotokopi KK', 'wajib' => false],
            ],
        ]);

        $this->actingAs($warga)->post(route('client.surat.store'), [
            'surat_type_id' => $type->id,
            'syarat' => [
                0 => UploadedFile::fake()->create('ktp.pdf', 20, 'application/pdf'),
            ],
        ]);

        $surat = SuratRequest::latest('id')->first();
        $this->assertNotNull($surat);
        $this->assertDatabaseHas('surat_documents', [
            'surat_request_id' => $surat->id,
            'jenis_dokumen' => 'syarat',
            'nama_file' => 'Fotokopi KTP',
        ]);
    }
}
