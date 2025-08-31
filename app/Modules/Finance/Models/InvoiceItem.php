<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'finance_invoice_items';

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'line_total',
        'tax_rate',
        'tax_amount',
        'sort_order'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2'
    ];

    /**
     * Get the invoice this item belongs to
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Calculate line total
     */
    public function calculateTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $tax_amount = $subtotal * ($this->tax_rate / 100);
        $total = $subtotal + $tax_amount;

        $this->update([
            'tax_amount' => $tax_amount,
            'line_total' => $total
        ]);

        return $this;
    }

    /**
     * Boot method to calculate totals automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $subtotal = $item->quantity * $item->unit_price;
            $tax_amount = $subtotal * (($item->tax_rate ?? 0) / 100);
            $item->tax_amount = $tax_amount;
            $item->line_total = $subtotal + $tax_amount;
        });
    }
}
