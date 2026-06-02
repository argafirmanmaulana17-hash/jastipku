<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jastiper_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('jastiper_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->bigInteger('amount')->default(1000);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');

            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jastiper_bills');
    }
};