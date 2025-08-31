<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PropertyTaxPayment extends Model
{
    use HasFactory;

    protected $table = 'property_tax_payments';

    protected $fillable = [
        'payment_reference',
        'bill_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'receipt_number',
        'payment_notes',
        'status',
        'processed_by'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function bill()
    {
        return $this->belongsTo(PropertyTaxBill::class, 'bill_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
