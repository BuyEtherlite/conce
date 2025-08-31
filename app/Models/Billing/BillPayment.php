<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Council;
use App\Models\User;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_reference',
        'bill_id',
        'customer_account_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'transaction_reference',
        'receipt_number',
        'status',
        'notes',
        'processed_by',
        'council_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function bill()
    {
        return $this->belongsTo(MunicipalBill::class, 'bill_id');
    }

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            if ($payment->status === 'completed') {
                $payment->updateBillPaymentStatus();
            }
        });
    }

    public function updateBillPaymentStatus()
    {
        $bill = $this->bill;
        $bill->paid_amount += $this->amount;
        $bill->outstanding_amount = $bill->total_amount - $bill->paid_amount;
        
        if ($bill->outstanding_amount <= 0) {
            $bill->status = 'paid';
        }
        
        $bill->save();
        $bill->customerAccount->updateBalance();
    }
}
