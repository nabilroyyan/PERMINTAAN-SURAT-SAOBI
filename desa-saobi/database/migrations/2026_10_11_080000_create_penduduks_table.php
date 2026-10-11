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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->index();
            $table->string('no_kk', 16)->index();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->default('Laki-laki'); // Laki-laki / Perempuan
            $table->text('alamat')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status_perkawinan', 50)->nullable();
            $table->string('telepon', 20)->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
