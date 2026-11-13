<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_requests', function (Blueprint $table) {
            $table->string('verification_token', 64)->nullable()->unique()->after('file_surat');
        });

        // Isi token untuk surat yang sudah ada agar tautan verifikasi tetap berfungsi
        foreach (DB::table('surat_requests')->whereNull('verification_token')->pluck('id') as $id) {
            DB::table('surat_requests')
                ->where('id', $id)
                ->update(['verification_token' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('surat_requests', function (Blueprint $table) {
            $table->dropUnique(['verification_token']);
            $table->dropColumn('verification_token');
        });
    }
};
