<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi massal dari controller/seeder.
    protected $fillable = [
        'nama',
        'toko',
        'kategori',
        'harga',
        'aktif',
    ];

    // Mengubah tipe data otomatis.
    // harga jadi angka integer, aktif jadi true/false.
    protected $casts = [
        'harga' => 'integer',
        'aktif' => 'boolean',
    ];

    // Relasi: satu menu price list bisa dipakai oleh banyak order.
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
