<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_harga','menunggu_persetujuan','proses','otw','selesai','batal') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'menunggu_harga')
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_persetujuan','proses','otw','selesai','batal') DEFAULT 'pending'");
    }
};
