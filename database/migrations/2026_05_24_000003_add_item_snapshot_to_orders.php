<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'nama_item_snapshot')) {
                $table->string('nama_item_snapshot')->nullable()->after('menu_item_id');
            }

            if (! Schema::hasColumn('orders', 'toko_snapshot')) {
                $table->string('toko_snapshot')->nullable()->after('nama_item_snapshot');
            }

            if (! Schema::hasColumn('orders', 'kategori_item_snapshot')) {
                $table->string('kategori_item_snapshot')->nullable()->after('toko_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'kategori_item_snapshot')) {
                $table->dropColumn('kategori_item_snapshot');
            }

            if (Schema::hasColumn('orders', 'toko_snapshot')) {
                $table->dropColumn('toko_snapshot');
            }

            if (Schema::hasColumn('orders', 'nama_item_snapshot')) {
                $table->dropColumn('nama_item_snapshot');
            }
        });
    }
};
