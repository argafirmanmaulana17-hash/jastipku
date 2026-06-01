<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPriceOffer extends Model
{
    protected $fillable = [
        'order_id',
        'sender_id',
        'sender_role',
        'harga_barang',
        'ongkos_jastip',
        'total_bayar',
        'catatan',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
