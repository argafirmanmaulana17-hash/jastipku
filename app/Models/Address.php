<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'label', 'alamat_lengkap'];

    // Hubungkan ke data User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
