<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastiperBill extends Model
{
    protected $fillable = [
        'order_id',
        'jastiper_id',
        'amount',
        'status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function jastiper()
    {
        return $this->belongsTo(User::class, 'jastiper_id');
    }
}
