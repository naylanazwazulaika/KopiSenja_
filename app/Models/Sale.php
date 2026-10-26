<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'sale_number',
        'customer_name',
        'customer_phone',
        'order_type',
        'total',
        'payment_method',
        'status',
        'notes',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Sedang Disiapkan',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getOrderTypeLabelAttribute(): string
    {
        return match($this->order_type) {
            'dine_in' => 'Dine In (Makan di Tempat)',
            'take_away' => 'Take Away (Bawa Pulang)',
            'pos' => 'Kasir / Walk-in',
            default => ucfirst(str_replace('_', ' ', $this->order_type ?? 'pos')),
        };
    }

    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeCancelledByStaff(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeCancelledByAdmin(): bool
    {
        return $this->status !== 'cancelled';
    }
}
