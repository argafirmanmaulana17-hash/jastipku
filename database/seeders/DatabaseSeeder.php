<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // ADMIN
        // =====================
        User::create([
            'name' => 'Admin JastipKu',
            'email' => 'admin@jastipku.com',
            'password' => Hash::make('password'),
            'whatsapp' => '081200000001',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // =====================
        // JASTIPERS
        // =====================
        $jastipers = [
            ['name' => 'Ahmad Rizki',   'email' => 'rizki@jastipku.com',   'area' => 'Kantin Utama & Koperasi', 'kendaraan' => 'motor',  'rating' => 4.9, 'total' => 48],
            ['name' => 'Siti Rahma',    'email' => 'rahma@jastipku.com',   'area' => 'Mall Dekat Kampus',       'kendaraan' => 'motor',  'rating' => 5.0, 'total' => 62],
            ['name' => 'Budi Santoso',  'email' => 'budi@jastipku.com',    'area' => 'Fotokopi & Percetakan',  'kendaraan' => 'sepeda', 'rating' => 4.8, 'total' => 31],
            ['name' => 'Dewi Lestari',  'email' => 'dewi@jastipku.com',    'area' => 'Warung & Minimarket',     'kendaraan' => 'motor',  'rating' => 4.7, 'total' => 25],
        ];

        foreach ($jastipers as $j) {
            User::create([
                'name' => $j['name'],
                'email' => $j['email'],
                'password' => Hash::make('password'),
                'whatsapp' => '0812'.rand(10000000, 99999999),
                'role' => 'jastiper',
                'area_layanan' => $j['area'],
                'kendaraan' => $j['kendaraan'],
                'status' => 'aktif',
                'rating' => $j['rating'],
                'total_order' => $j['total'],
            ]);
        }

        // =====================
        // USERS (Pembeli)
        // =====================
        $users = [
            ['name' => 'Dina Fitria',   'email' => 'dina@student.ac.id'],
            ['name' => 'Reza Maulana',  'email' => 'reza@student.ac.id'],
            ['name' => 'Ayu Putri',     'email' => 'ayu@student.ac.id'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@student.ac.id'],
            ['name' => 'Nisa Amalia',   'email' => 'nisa@student.ac.id'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('password'),
                'whatsapp' => '0813'.rand(10000000, 99999999),
                'role' => 'user',
            ]);
        }

        // =====================
        // SAMPLE ORDERS
        // =====================
        $sampleOrders = [
            ['nama' => 'Dina Fitria',   'kategori' => 'makanan',    'item' => 'Nasi goreng ayam + Es teh manis', 'lokasi_ambil' => 'Kantin Utama',        'lokasi_antar' => 'Gedung A Lt.2',     'budget' => 20000,  'status' => 'selesai'],
            ['nama' => 'Reza Maulana',  'kategori' => 'atk',        'item' => 'Pulpen Pilot G2 x3, Buku tulis A5 x2', 'lokasi_ambil' => 'Koperasi Kampus', 'lokasi_antar' => 'Perpustakaan',      'budget' => 35000,  'status' => 'selesai'],
            ['nama' => 'Ayu Putri',     'kategori' => 'minimarket',  'item' => 'Aqua 1.5L x2, Indomie goreng x3, Tisu', 'lokasi_ambil' => 'Indomaret Jl. Kampus', 'lokasi_antar' => 'Kos Blok B No.5', 'budget' => 45000, 'status' => 'otw'],
            ['nama' => 'Fajar Nugroho', 'kategori' => 'makanan',    'item' => 'Mie Ayam Bakso + Jus Alpukat', 'lokasi_ambil' => 'Warung Bu Sari',       'lokasi_antar' => 'Lab Komputer',      'budget' => 25000,  'status' => 'proses'],
            ['nama' => 'Nisa Amalia',   'kategori' => 'obat',       'item' => 'Paracetamol 500mg x10, Vitamin C', 'lokasi_ambil' => 'Apotek Sehat',     'lokasi_antar' => 'Asrama Putri',      'budget' => 30000,  'status' => 'pending'],
            ['nama' => 'Dina Fitria',   'kategori' => 'makanan',    'item' => 'Ayam geprek level 3 + Rice box', 'lokasi_ambil' => 'Warung Geprek Pak Joko', 'lokasi_antar' => 'Ruang Kelas 301', 'budget' => 22000, 'status' => 'pending'],
        ];

        $jastiperIds = User::where('role', 'jastiper')->pluck('id')->toArray();
        $userIds = User::where('role', 'user')->pluck('id')->toArray();

        foreach ($sampleOrders as $i => $o) {
            $year = date('Y');
            $count = $i + 1;
            Order::create([
                'kode_order' => 'JK-'.$year.'-'.str_pad($count, 4, '0', STR_PAD_LEFT),
                'user_id' => $userIds[array_rand($userIds)],
                'jastiper_id' => in_array($o['status'], ['proses', 'otw', 'selesai']) ? $jastiperIds[array_rand($jastiperIds)] : null,
                'nama' => $o['nama'],
                'whatsapp' => '0812'.rand(10000000, 99999999),
                'kategori' => $o['kategori'],
                'lokasi_ambil' => $o['lokasi_ambil'],
                'lokasi_antar' => $o['lokasi_antar'],
                'detail_pesanan' => $o['item'],
                'budget' => $o['budget'],
                'waktu' => 'segera',
                'pembayaran' => ['dana', 'cash', 'transfer'][array_rand(['dana', 'cash', 'transfer'])],
                'status' => $o['status'],
                'dp_paid' => $o['status'] !== 'pending',
            ]);
        }
    }
}
