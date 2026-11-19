<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('village_settings', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('nama_desa');
            $table->string('kabupaten')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kabupaten');
            $table->string('kode_pos', 10)->nullable()->after('alamat');
            $table->string('telepon', 30)->nullable()->after('kode_pos');
            $table->string('email')->nullable()->after('telepon');
            $table->string('website')->nullable()->after('email');
            $table->string('logo_kiri')->nullable()->after('gambar_tanda_tangan');
            $table->string('logo_kanan')->nullable()->after('logo_kiri');
        });
    }

    public function down(): void
    {
        Schema::table('village_settings', function (Blueprint $table) {
            $table->dropColumn([
                'kecamatan',
                'kabupaten',
                'provinsi',
                'kode_pos',
                'telepon',
                'email',
                'website',
                'logo_kiri',
                'logo_kanan',
            ]);
        });
    }
};
