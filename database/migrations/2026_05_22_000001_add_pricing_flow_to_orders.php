<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('toko')->nullable();
                $table->string('kategori')->default('makanan');
                $table->bigInteger('harga')->default(0);
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });

            DB::table('menu_items')->insert([
                [
                    'nama' => 'Es Teh',
                    'toko' => 'Kantin Kampus',
                    'kategori' => 'minuman',
                    'harga' => 3000,
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama' => 'Mie Ayam',
                    'toko' => 'Kantin Kampus',
                    'kategori' => 'makanan',
                    'harga' => 10000,
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama' => 'Nasi Goreng',
                    'toko' => 'Kantin Kampus',
                    'kategori' => 'makanan',
                    'harga' => 12000,
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'jenis_harga')) {
                $table->enum('jenis_harga', ['pricelist', 'penawaran'])
                    ->default('penawaran')
                    ->after('kategori');
            }

            if (! Schema::hasColumn('orders', 'menu_item_id')) {
                $table->foreignId('menu_item_id')
                    ->nullable()
                    ->after('jenis_harga')
                    ->constrained('menu_items')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'harga_barang')) {
                $table->bigInteger('harga_barang')->nullable()->after('budget');
            }

            if (! Schema::hasColumn('orders', 'ongkos_jastip')) {
                $table->bigInteger('ongkos_jastip')->nullable()->after('harga_barang');
            }

            if (! Schema::hasColumn('orders', 'catatan_harga')) {
                $table->text('catatan_harga')->nullable()->after('catatan');
            }

            if (! Schema::hasColumn('orders', 'harga_disetujui_at')) {
                $table->timestamp('harga_disetujui_at')->nullable()->after('catatan_harga');
            }
        });

        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','menunggu_persetujuan','proses','otw','selesai','batal') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'menunggu_persetujuan')
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','proses','otw','selesai','batal') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'menu_item_id')) {
                $table->dropConstrainedForeignId('menu_item_id');
            }

            if (Schema::hasColumn('orders', 'jenis_harga')) {
                $table->dropColumn('jenis_harga');
            }

            if (Schema::hasColumn('orders', 'harga_barang')) {
                $table->dropColumn('harga_barang');
            }

            if (Schema::hasColumn('orders', 'ongkos_jastip')) {
                $table->dropColumn('ongkos_jastip');
            }

            if (Schema::hasColumn('orders', 'catatan_harga')) {
                $table->dropColumn('catatan_harga');
            }

            if (Schema::hasColumn('orders', 'harga_disetujui_at')) {
                $table->dropColumn('harga_disetujui_at');
            }
        });

        Schema::dropIfExists('menu_items');
    }
};
