<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Role kepala_desa dihapus; akun yang masih memakainya dijadikan admin.
     */
    public function up(): void
    {
        DB::table('users')->where('role', 'kepala_desa')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // Tidak dapat dikembalikan: akun lama tidak bisa dibedakan dari admin biasa.
    }
};

