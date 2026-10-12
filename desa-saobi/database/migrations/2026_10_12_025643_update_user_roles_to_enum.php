<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan semua role lama 'client' terkonversi ke 'warga'
        DB::table('users')->where('role', 'client')->update(['role' => 'warga']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('warga')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('client')->change();
        });
    }
};
