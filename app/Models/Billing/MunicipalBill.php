<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Council;

class MunicipalBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'customer_account_id',
        'bill_date',
        'due_date',
        'billing_period',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'penalty_amount',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'status',
        'notes',
        'sent_at',
        'council_id'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'sent_at' => 'timestamp'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function lineItems()
    {
        return $this->hasMany(BillLineItem::class, 'bill_id');
    }

    public function payments()
    {
        return $this->hasMany(BillPayment::class, 'bill_id');
    }

    public function reminders()
    {
        return $this->hasMany(BillReminder::class, 'bill_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->lineItems()->sum('amount');
        $this->tax_amount = $this->lineItems()->sum('tax_amount');
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->penalty_amount - $this->discount_amount;
        $this->outstanding_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_amount = $this->total_amount;
        $this->outstanding_amount = 0;
        $this->save();
        
        // Update customer balance
        $this->customerAccount->updateBalance();
    }

    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== 'paid';
    }
}
