<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalReceiptItem extends Model
{
    protected $fillable = [
        'fiscal_receipt_id',
        'item_code',
        'item_name',
        'item_description',
        'quantity',
        'unit_price',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'line_total',
        'tax_category',
        'unit_of_measure',
        'item_category'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'line_total' => 'decimal:2'
    ];

    const TAX_CATEGORY_STANDARD = 'standard'; // 15% VAT
    const TAX_CATEGORY_ZERO_RATED = 'zero_rated'; // 0% VAT
    const TAX_CATEGORY_EXEMPT = 'exempt'; // No VAT
    const TAX_CATEGORY_INTERMEDIATE = 'intermediate'; // Other rates

    public function fiscalReceipt()
    {
        return $this->belongsTo(FiscalReceipt::class);
    }

    public function calculateTax()
    {
        $taxableAmount = ($this->quantity * $this->unit_price) - $this->discount_amount;
        return round($taxableAmount * ($this->tax_rate / 100), 2);
    }

    public function calculateLineTotal()
    {
        $subtotal = ($this->quantity * $this->unit_price) - $this->discount_amount;
        return round($subtotal + $this->tax_amount, 2);
    }
}
