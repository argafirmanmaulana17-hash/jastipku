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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            // Relasi ke pesanan mana chat ini berlangsung
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Siapa yang mengirim pesan (bisa user pembeli atau jastiper)
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            // Isi pesannya
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
