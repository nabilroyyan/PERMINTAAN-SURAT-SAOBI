<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $konten = implode("\n", [
            'Yang bertanda tangan di bawah ini Kepala {nama_desa}, Kecamatan {kecamatan}, Kabupaten {kabupaten}, dengan ini menerangkan dengan sebenarnya bahwa:',
            '',
            '{tabel_pemohon}',
            '',
            'Adalah benar berdomisili dan bertempat tinggal di {alamat} dan berkeperluan {keperluan}.',
            '',
            'Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',
        ]);

        // Hanya buat bila template ini belum ada.
        $templateId = DB::table('surat_templates')
            ->where('nama', 'Surat Keterangan Domisili')
            ->value('id');

        if (!$templateId) {
            $templateId = DB::table('surat_templates')->insertGetId([
                'nama' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Template isi surat keterangan domisili standar Desa Saobi.',
                'konten' => $konten,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tautkan ke jenis surat SKD yang belum memiliki template.
        DB::table('surat_types')
            ->where('kode', 'SKD')
            ->whereNull('surat_template_id')
            ->update(['surat_template_id' => $templateId]);
    }

    public function down(): void
    {
        // Tidak menghapus data template agar aman.
    }
};
