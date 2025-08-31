<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ap_bills';

    protected $fillable = [
        'bill_number',
        'vendor_id',
        'vendor_invoice_number',
        'bill_date',
        'due_date',
        'description',
        'subtotal',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'balance_due',
        'status',
        'notes',
        'created_by',
        'approved_by',
        'approved_date'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'approved_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2'
    ];

    public function vendor()
    {
        return $this->belongsTo(Supplier::class, 'vendor_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(PaymentVoucher::class, 'bill_id');
    }
}
