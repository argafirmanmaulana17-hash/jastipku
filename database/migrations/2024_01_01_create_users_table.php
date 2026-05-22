<?php

// =====================================================
// LETAKKAN FILE INI DI: database/migrations/
// Nama file: 2024_01_01_000001_create_users_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('whatsapp', 20)->nullable();
            $table->enum('role', ['admin', 'jastiper', 'user'])->default('user');
            // Kolom khusus jastiper
            $table->string('area_layanan')->nullable();
            $table->enum('kendaraan', ['motor', 'sepeda', 'jalan'])->nullable();
            $table->enum('status', ['aktif', 'sibuk', 'offline'])->default('offline');
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('total_order')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
