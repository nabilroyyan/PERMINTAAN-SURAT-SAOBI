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
 Schema::create('village_settings', function (Blueprint $table) {
    $table->id();
    $table->string('nama_desa');
    $table->string('nama_kepala_desa');
    $table->string('nip_kepala_desa')->nullable();
    $table->string('gambar_tanda_tangan')->nullable();
    $table->text('alamat')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_settings');
    }
};
