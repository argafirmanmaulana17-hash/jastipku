<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'message',
    ];

    // Relasi: 1 Chat dimiliki oleh 1 Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: 1 Chat dikirim oleh 1 User (Pengirim)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
