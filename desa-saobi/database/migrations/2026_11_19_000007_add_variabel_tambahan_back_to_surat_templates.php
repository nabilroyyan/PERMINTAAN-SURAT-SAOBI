<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('surat_templates', 'variabel_tambahan')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                $table->json('variabel_tambahan')->nullable()->after('konten');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_templates', 'variabel_tambahan')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                $table->dropColumn('variabel_tambahan');
            });
        }
    }
};
