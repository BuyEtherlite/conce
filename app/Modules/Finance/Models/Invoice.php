
<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\User;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_invoices';

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'payment_status',
        'currency',
        'exchange_rate',
        'notes',
        'terms_conditions',
        'reference_number'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    protected $attributes = [
        'status' => 'draft',
        'payment_status' => 'pending',
        'currency' => 'USD',
        'exchange_rate' => 1.0000
    ];

    /**
     * Get the customer this invoice belongs to
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created this invoice
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get invoice items
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get payments for this invoice
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope for pending invoices
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('payment_status', '!=', 'paid');
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = static::whereYear('created_at', $year)->latest()->first();
        $nextNumber = $lastInvoice ? (intval(substr($lastInvoice->invoice_number, -6)) + 1) : 1;
        
        return 'INV-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate totals
     */
    public function calculateTotals()
    {
        $subtotal = $this->items()->sum(\DB::raw('quantity * unit_price'));
        $tax_amount = $subtotal * 0.15; // Assuming 15% tax
        $total = $subtotal + $tax_amount - $this->discount_amount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'total_amount' => $total
        ]);

        return $this;
    }

    /**
     * Get amount paid
     */
    public function getAmountPaid()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Get balance due
     */
    public function getBalanceDue()
    {
        return $this->total_amount - $this->getAmountPaid();
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid()
    {
        return $this->getBalanceDue() <= 0;
    }

    /**
     * Mark as sent
     */
    public function markAsSent()
    {
        $this->update(['status' => 'sent']);
        return $this;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid()
    {
        $this->update(['payment_status' => 'paid']);
        return $this;
    }
}
