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
      Schema::create('surat_documents', function (Blueprint $table) {
    $table->id();

    $table->foreignId('surat_request_id')
        ->constrained('surat_requests')
        ->cascadeOnDelete();

    $table->string('nama_file');
    $table->string('path_file');
    $table->string('jenis_dokumen');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_documents');
    }
};
