<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'whatsapp',
        'role', // admin, jastiper, user
        'area_layanan',
        'kendaraan',
        'status', // aktif, sibuk, offline (untuk jastiper)
        'rating',
        'total_order',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'rating' => 'float',
        'total_order' => 'integer',
    ];

    // ==========================================
    // ROLE HELPERS
    // ==========================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isJastiper(): bool
    {
        return $this->role === 'jastiper';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function jastiperOrders()
    {
        return $this->hasMany(Order::class, 'jastiper_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'jastiper_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeJastipers($query)
    {
        return $query->where('role', 'jastiper');
    }

    public function scopeActiveJastipers($query)
    {
        return $query->where('role', 'jastiper')->where('status', 'aktif');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
    public function addresses()
{
    return $this->hasMany(Address::class);
}
}
