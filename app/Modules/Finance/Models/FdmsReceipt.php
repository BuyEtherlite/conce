<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules;


class FdmsReceipt extends Model
{
    use SoftDeletes;

    protected $table = 'fdms_receipts';

    protected $fillable = [
        'receipt_number',
        'fiscal_receipt_number',
        'customer_id',
        'cashier_id',
        'receipt_date',
        'receipt_time',
        'payment_method',
        'currency_code',
        'exchange_rate',
        'subtotal_amount',
        'tax_amount',
        'total_amount',
        'amount_tendered',
        'change_amount',
        'fiscal_device_id',
        'fiscal_signature',
        'qr_code',
        'verification_code',
        'fdms_transmitted',
        'fdms_transmission_date',
        'fdms_response',
        'status',
        'voided_at',
        'void_reason',
        'original_receipt_id',
        'items'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'receipt_time' => 'datetime',
        'exchange_rate' => 'decimal:4',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_tendered' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'fdms_transmitted' => 'boolean',
        'fdms_transmission_date' => 'datetime',
        'voided_at' => 'datetime',
        'items' => 'json'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function fiscalDevice()
    {
        return $this->belongsTo(FiscalDevice::class);
    }

    public function originalReceipt()
    {
        return $this->belongsTo(FdmsReceipt::class, 'original_receipt_id');
    }

    public function voidedReceipts()
    {
        return $this->hasMany(FdmsReceipt::class, 'original_receipt_id');
    }

    public static function generateReceiptNumber()
    {
        $prefix = 'RCT';
        $date = now()->format('Ymd');
        $sequence = static::whereDate('receipt_date', now())->count() + 1;
        
        return $prefix . $date . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    public function generateFiscalSignature()
    {
        // Generate FDMS compliant fiscal signature
        $data = [
            'receipt_number' => $this->receipt_number,
            'total_amount' => $this->total_amount,
            'tax_amount' => $this->tax_amount,
            'receipt_date' => $this->receipt_date->format('Y-m-d'),
            'fiscal_device_id' => $this->fiscal_device_id
        ];
        
        return hash('sha256', json_encode($data));
    }

    public function generateQrCode()
    {
        // Generate QR code for FDMS compliance
        $qrData = [
            'receipt_number' => $this->receipt_number,
            'fiscal_receipt_number' => $this->fiscal_receipt_number,
            'total_amount' => $this->total_amount,
            'tax_amount' => $this->tax_amount,
            'date' => $this->receipt_date->format('Y-m-d'),
            'verification_code' => $this->verification_code
        ];
        
        return base64_encode(json_encode($qrData));
    }

    public function transmitToFdms()
    {
        // Simulate FDMS transmission
        $transmissionData = [
            'receipt_number' => $this->receipt_number,
            'fiscal_device_id' => $this->fiscal_device_id,
            'total_amount' => $this->total_amount,
            'tax_amount' => $this->tax_amount,
            'currency_code' => $this->currency_code,
            'items' => $this->items,
            'fiscal_signature' => $this->fiscal_signature
        ];

        // Update transmission status
        $this->update([
            'fdms_transmitted' => true,
            'fdms_transmission_date' => now(),
            'fdms_response' => json_encode(['status' => 'success', 'transmission_id' => uniqid()])
        ]);

        return true;
    }

    public function isFdmsCompliant()
    {
        return $this->fdms_transmitted && 
               !empty($this->fiscal_signature) && 
               !empty($this->verification_code) &&
               !empty($this->qr_code);
    }

    public function scopeTransmitted($query)
    {
        return $query->where('fdms_transmitted', true);
    }

    public function scopePending($query)
    {
        return $query->where('fdms_transmitted', false);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('receipt_date', [$startDate, $endDate]);
    }
}
