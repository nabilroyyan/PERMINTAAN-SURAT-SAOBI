<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->json('form_fields')->nullable()->after('persyaratan');
        });

        // Pindahkan definisi variabel tambahan dari template ke masing-masing jenis surat.
        $types = DB::table('surat_types')->get(['id', 'surat_template_id']);

        foreach ($types as $type) {
            $fields = null;

            if (!empty($type->surat_template_id)) {
                $template = DB::table('surat_templates')
                    ->where('id', $type->surat_template_id)
                    ->first();

                if ($template && !empty($template->variabel_tambahan)) {
                    $decoded = json_decode($template->variabel_tambahan, true);
                    if (is_array($decoded) && count($decoded) > 0) {
                        $fields = $decoded;
                    }
                }
            }

            if ($fields === null) {
                $fields = [
                    ['kode' => 'alamat', 'label' => 'Alamat Domisili', 'tipe' => 'textarea', 'wajib' => true, 'placeholder' => null, 'help' => null, 'options' => []],
                    ['kode' => 'keperluan', 'label' => 'Keperluan Surat', 'tipe' => 'textarea', 'wajib' => true, 'placeholder' => null, 'help' => null, 'options' => []],
                ];
            }

            DB::table('surat_types')->where('id', $type->id)->update([
                'form_fields' => json_encode($fields),
            ]);
        }

        Schema::table('surat_templates', function (Blueprint $table) {
            $table->dropColumn('variabel_tambahan');
        });
    }

    public function down(): void
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            $table->json('variabel_tambahan')->nullable()->after('konten');
        });

        Schema::table('surat_types', function (Blueprint $table) {
            $table->dropColumn('form_fields');
        });
    }
};
