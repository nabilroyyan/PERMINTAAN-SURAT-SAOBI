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
            $table->string('format_nomor')->nullable()->after('kode');
            $table->unsignedInteger('nomor_terakhir')->default(0)->after('format_nomor');
            $table->unsignedTinyInteger('panjang_nomor')->default(2)->after('nomor_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->dropColumn(['format_nomor', 'nomor_terakhir', 'panjang_nomor']);
        });
    }
};
