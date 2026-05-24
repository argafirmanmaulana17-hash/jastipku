<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappOtp extends Model
{
    protected $fillable = [
        'user_id',
        'whatsapp',
        'otp_hash',
        'expired_at',
        'verified_at',
        'attempts',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
