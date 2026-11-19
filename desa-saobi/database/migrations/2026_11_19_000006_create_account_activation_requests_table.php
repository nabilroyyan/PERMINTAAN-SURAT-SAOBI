<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_activation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->index();
            $table->string('nama')->nullable();
            $table->string('email');
            $table->string('password_plain');
            $table->string('status', 20)->default('pending')->index();
            $table->text('catatan')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_activation_requests');
    }
};
