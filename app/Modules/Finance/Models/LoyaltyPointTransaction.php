<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_id',
        'type',
        'points',
        'description',
        'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date'
    ];

    const TYPES = [
        'earned' => 'Earned',
        'redeemed' => 'Redeemed',
        'expired' => 'Expired',
        'adjustment' => 'Adjustment'
    ];

    /**
     * Get the customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get type display name
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Scope for earned points
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope for redeemed points
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope for expired points
     */
    public function scopeExpired($query)
    {
        return $query->where('type', 'expired');
    }

    /**
     * Scope for points expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('type', 'earned')
                    ->whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if transaction is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= $days;
    }
}