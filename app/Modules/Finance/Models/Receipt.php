<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'ar_receipts';

    protected $fillable = [
        'receipt_number',
        'customer_id',
        'invoice_id',
        'receipt_date',
        'amount_received',
        'payment_method',
        'reference_number',
        'notes',
        'received_by'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount_received' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(ArInvoice::class, 'invoice_id');
    }

    public function receiver()
    {
        return $this->belongsTo(\App\Models\User::class, 'received_by');
    }
}
