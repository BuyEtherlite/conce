<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pos_payments';

    protected $fillable = [
        'payment_number',
        'payment_date', 
        'total_amount',
        'payment_method',
        'terminal_id',
        'status',
        'payment_details',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'payment_details' => 'array'
    ];

    const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'card' => 'Credit/Debit Card',
        'mobile_money' => 'Mobile Money',
        'bank_transfer' => 'Bank Transfer',
        'cheque' => 'Cheque'
    ];

    const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed', 
        'failed' => 'Failed',
        'cancelled' => 'Cancelled'
    ];

    /**
     * Get the terminal that processed this payment
     */
    public function terminal()
    {
        return $this->belongsTo(PosTerminal::class, 'terminal_id');
    }

    /**
     * Get the user who created this payment
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the receipt for this payment
     */
    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }

    /**
     * Scope for successful payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for payments by method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for payments in date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodNameAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status display name
     */
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Check if payment is successful
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment can be refunded
     */
    public function canBeRefunded()
    {
        return $this->isCompleted() && $this->created_at->diffInDays(now()) <= 30;
    }

    /**
     * Calculate loyalty points earned
     */
    public function calculateLoyaltyPoints()
    {
        // 1 point per dollar spent, minimum 1 point
        return max(1, floor($this->total_amount));
    }
}
