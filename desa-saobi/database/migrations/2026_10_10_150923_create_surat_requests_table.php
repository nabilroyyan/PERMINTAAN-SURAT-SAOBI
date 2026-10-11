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
       Schema::create('surat_requests', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained('users')
        ->restrictOnDelete();

    $table->foreignId('surat_type_id')
        ->constrained('surat_types')
        ->restrictOnDelete();

    $table->json('data_pemohon');
    $table->string('status')->default('pending');

    $table->string('nomor_surat')->nullable()->unique();
    $table->date('tanggal_surat')->nullable();

    $table->text('catatan_admin')->nullable();
    $table->string('file_surat')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_requests');
    }
};
