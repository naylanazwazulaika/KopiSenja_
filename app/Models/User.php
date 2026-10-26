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
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwner(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['staff', 'admin_staff', 'staff_admin']);
    }

    public function isCustomer(): bool
    {
        return in_array($this->role, ['customer', 'pelanggan']);
    }

    public function isPelanggan(): bool
    {
        return $this->isCustomer();
    }

    public function canManageMaster(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function processedSales()
    {
        return $this->hasMany(Sale::class, 'staff_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
