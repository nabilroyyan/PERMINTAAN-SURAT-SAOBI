<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->string('kode')->unique()->after('nama');
            $table->text('deskripsi')->nullable()->after('kode');
            $table->json('persyaratan')->nullable()->after('deskripsi');
            $table->boolean('is_active')->default(true)->after('persyaratan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->dropColumn(['nama', 'kode', 'deskripsi', 'persyaratan', 'is_active']);
        });
    }
};
