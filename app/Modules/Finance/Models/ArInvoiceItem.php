<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'ar_invoice_items';

    protected $fillable = [
        'ar_invoice_id',
        'item_description',
        'quantity',
        'unit_price',
        'line_total',
        'account_code',
        'tax_code',
        'tax_amount'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'tax_amount' => 'decimal:2'
    ];

    public function invoice()
    {
        return $this->belongsTo(ArInvoice::class, 'ar_invoice_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->line_total = $item->quantity * $item->unit_price;
        });
    }
}
