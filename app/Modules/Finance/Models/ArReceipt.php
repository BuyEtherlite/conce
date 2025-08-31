<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules;


class ArReceipt extends Model
{
    use HasFactory;

    protected $table = 'ar_receipts';

    protected $fillable = [
        'receipt_number',
        'customer_id',
        'ar_invoice_id',
        'receipt_date',
        'amount_received',
        'payment_method',
        'payment_reference',
        'bank_account_id',
        'notes',
        'council_id',
        'department_id',
        'office_id',
        'created_by'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount_received' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(ArInvoice::class, 'ar_invoice_id');
    }

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($receipt) {
            // Update invoice balance when receipt is created
            if ($receipt->ar_invoice_id) {
                $invoice = $receipt->invoice;
                $totalReceived = $invoice->receipts()->sum('amount_received');
                $newBalance = $invoice->amount - $totalReceived;
                
                $invoice->update([
                    'balance_due' => max(0, $newBalance),
                    'status' => $newBalance <= 0 ? 'paid' : $invoice->status
                ]);
            }
        });
    }
}
