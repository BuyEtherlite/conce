<?php

namespace App\Modules\Finance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'revenue_collections';

    protected $fillable = [
        'receipt_number',
        'revenue_source',
        'source_reference',
        'customer_id',
        'collection_date',
        'amount_collected',
        'payment_method',
        'payment_reference',
        'collected_by',
        'notes'
    ];

    protected $casts = [
        'amount_collected' => 'decimal:2',
        'collection_date' => 'date'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function auditTrail(): HasMany
    {
        return $this->hasMany(CollectionAuditTrail::class);
    }

    public function revenueSource()
    {
        return $this->belongsTo(RevenueSource::class);
    }

    // public function collector() // Redefined in edited snippet, keeping the one from edited snippet
    // {
    //     return $this->belongsTo(\App\Models\User::class, 'collector_id');
    // }

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->receipt_number)) {
                $collection->receipt_number = static::generateReceiptNumber();
            }

            if (empty($collection->collected_at)) {
                $collection->collected_at = now();
            }
        });

        static::created(function ($collection) {
            // Create audit trail entry
            CollectionAuditTrail::create([
                'collection_id' => $collection->id,
                'action' => 'created',
                'new_data' => $collection->toArray(),
                'performed_by' => auth()->id() ?? $collection->collected_by,
                'ip_address' => request()->ip()
            ]);

            // Post to General Ledger
            $collection->postToGeneralLedger();
        });
    }

    public static function generateReceiptNumber()
    {
        $prefix = 'RCT';
        $year = date('Y');
        $month = date('m');

        $lastReceipt = static::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month)
                           ->orderBy('id', 'desc')
                           ->first();

        $sequence = $lastReceipt ? (intval(substr($lastReceipt->receipt_number, -6)) + 1) : 1;

        return $prefix . $year . $month . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public function postToGeneralLedger()
    {
        $accountType = $this->customerAccount->accountType;

        // Credit Cash/Bank Account
        GeneralLedger::create([
            'transaction_date' => $this->collected_at->format('Y-m-d'),
            'account_code' => $this->paymentMethod->code === 'CASH' ? '1010' : '1020', // Cash or Bank
            'debit_amount' => $this->amount_paid,
            'credit_amount' => 0,
            'description' => "Payment received from {$this->customerAccount->customer->name} - {$this->receipt_number}",
            'reference_number' => $this->receipt_number,
            'source_module' => 'pos',
            'source_document_type' => 'collection',
            'source_document_id' => $this->id,
            'created_by' => $this->collected_by,
            'status' => 'posted'
        ]);

        // Debit Revenue Account
        GeneralLedger::create([
            'transaction_date' => $this->collected_at->format('Y-m-d'),
            'account_code' => $accountType->code === 'WATER' ? '4010' : '4020', // Water Revenue or Electricity Revenue
            'debit_amount' => 0,
            'credit_amount' => $this->amount_paid,
            'description' => "Payment received from {$this->customerAccount->customer->name} - {$this->receipt_number}",
            'reference_number' => $this->receipt_number,
            'source_module' => 'pos',
            'source_document_type' => 'collection',
            'source_document_id' => $this->id,
            'created_by' => $this->collected_by,
            'status' => 'posted'
        ]);
    }
}