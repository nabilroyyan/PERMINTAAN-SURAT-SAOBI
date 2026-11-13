<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->foreignId('surat_template_id')
                ->nullable()
                ->after('is_active')
                ->constrained('surat_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->dropConstrainedForeignId('surat_template_id');
        });
    }
};
