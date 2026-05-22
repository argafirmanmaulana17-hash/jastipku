<?php

// =====================================================
// LETAKKAN FILE INI DI: database/migrations/
// Nama file: 2024_01_01_000002_create_orders_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('jastiper_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama');
            $table->string('whatsapp', 20);
            $table->string('kategori', 50);
            $table->string('lokasi_ambil');
            $table->string('lokasi_antar');
            $table->text('detail_pesanan');
            $table->bigInteger('budget')->default(0);
            $table->bigInteger('total_bayar')->default(0);
            $table->enum('waktu', ['segera', '1jam', '2jam', 'hari_ini'])->default('segera');
            $table->enum('pembayaran', ['dana', 'cash', 'transfer'])->default('cash');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'proses', 'otw', 'selesai', 'batal'])->default('pending');
            $table->boolean('dp_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
