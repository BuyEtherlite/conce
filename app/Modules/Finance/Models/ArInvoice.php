<?php

namespace App\Modules\Finance\Models;

use App\Modules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'balance_due',
        'status',
        'description',
        'terms',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(ArInvoiceItem::class, 'invoice_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'invoice_id');
    }

    public function generalLedgerEntries()
    {
        return $this->morphMany(GeneralLedger::class, 'reference');
    }

    public function isOverdue()
    {
        return $this->due_date < now() && $this->balance_due > 0;
    }

    public function getPaidAmountAttribute()
    {
        return $this->amount - $this->balance_due;
    }
}