<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\User;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_payments';

    protected $fillable = [
        'payment_number',
        'customer_id',
        'invoice_id',
        'user_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'bank_account',
        'cheque_number',
        'status',
        'currency',
        'exchange_rate',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    protected $attributes = [
        'status' => 'completed',
        'currency' => 'USD',
        'exchange_rate' => 1.0000
    ];

    /**
     * Get the customer this payment belongs to
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the invoice this payment is for
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who recorded this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who processed this payment
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Generate payment number
     */
    public static function generatePaymentNumber()
    {
        $year = date('Y');
        $lastPayment = static::whereYear('created_at', $year)->latest()->first();
        $nextNumber = $lastPayment ? (intval(substr($lastPayment->payment_number, -6)) + 1) : 1;
        
        return 'PAY-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for payments by method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Process payment
     */
    public function process()
    {
        $this->update([
            'status' => 'completed',
            'processed_by' => auth()->id()
        ]);

        // Update invoice payment status if fully paid
        if ($this->invoice && $this->invoice->isPaid()) {
            $this->invoice->markAsPaid();
        }

        return $this;
    }
}
