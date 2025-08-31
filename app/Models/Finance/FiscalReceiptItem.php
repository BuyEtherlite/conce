<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FiscalReceiptItem extends Model
{
    protected $fillable = [
        'fiscal_receipt_id',
        'item_description',
        'quantity',
        'unit_price',
        'line_total',
        'tax_id',
        'tax_code',
        'tax_rate',
        'tax_amount',
        'discount_amount'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2'
    ];

    public function fiscalReceipt()
    {
        return $this->belongsTo(FiscalReceipt::class);
    }
}
