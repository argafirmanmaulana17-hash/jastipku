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
        'lokasi_ambil',
        'lokasi_antar',
        'detail_pesanan',
        'budget',
        'waktu',
        'pembayaran',
        'catatan',
        'status', // pending, proses, otw, selesai, batal
        'total_bayar',
        'dp_paid',
    ];

    protected $casts = [
        'budget' => 'integer',
        'total_bayar' => 'integer',
        'dp_paid' => 'boolean',
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
        return $query->whereIn('status', ['proses', 'otw']);
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
        return $prefix . '-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => '⏳ Menunggu',
            'proses' => '🔄 Diproses',
            'otw' => '🛵 OTW',
            'selesai' => '✅ Selesai',
            'batal' => '❌ Dibatal',
            default => ucfirst($this->status),
        };
    }
}
