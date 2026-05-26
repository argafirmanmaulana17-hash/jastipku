<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_order',
        'user_id',
        'jastiper_id',
        'nama',
        'whatsapp',
        'kategori',
        'jenis_harga',
        'menu_item_id',
        'lokasi_ambil',
        'lokasi_antar',
        'detail_pesanan',
        'budget',
        'harga_barang',
        'ongkos_jastip',
        'nama_item_snapshot',
        'toko_snapshot',
        'kategori_item_snapshot',
        'waktu',
        'pembayaran',
        'catatan',
        'catatan_harga',
        'harga_disetujui_at',
        'status',
        'total_bayar',
        'dp_paid',
    ];

    protected $casts = [
        'budget' => 'integer',
        'total_bayar' => 'integer',
        'dp_paid' => 'boolean',
        'harga_barang' => 'integer',
        'ongkos_jastip' => 'integer',
        'harga_disetujui_at' => 'datetime',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jastiper()
    {
        return $this->belongsTo(User::class, 'jastiper_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['menunggu_harga', 'menunggu_persetujuan', 'proses', 'otw']);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // ==========================================
    // HELPERS
    // ==========================================

    public static function generateKode(): string
    {
        $prefix = 'JK';
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        return $prefix.'-'.$year.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        switch ($this->status) {
            case 'pending':
                return '⏳ Menunggu';
            case 'menunggu_harga':
                return '💸 Menunggu Harga dari Jastiper';
            case 'menunggu_persetujuan':
                return '💰 Menunggu Persetujuan Harga';
            case 'proses':
                return '🔄 Diproses';
            case 'otw':
                return '🛵 OTW';
            case 'selesai':
                return '✅ Selesai';
            case 'batal':
                return '❌ Dibatalkan';
            default:
                return ucfirst($this->status);
        }
    }

    // Relasi ke tabel chats
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
