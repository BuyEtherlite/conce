<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FiscalReceipt extends Model
{
    protected $fillable = [
        'fiscal_device_id',
        'receipt_number',
        'fiscal_number',
        'qr_code',
        'receipt_type',
        'transaction_date',
        'customer_tin',
        'customer_name',
        'customer_address',
        'subtotal_amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'currency_code',
        'exchange_rate',
        'operator_id',
        'verification_code',
        'digital_signature',
        'receipt_data',
        'printed_at',
        'voided_at',
        'void_reason',
        'is_voided',
        'zimra_transmitted_at',
        'zimra_response',
        'compliance_status',
        'zimra_receipt_number',
        'fiscal_day_number',
        'rejection_reason'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'printed_at' => 'datetime',
        'voided_at' => 'datetime',
        'zimra_transmitted_at' => 'datetime',
        'receipt_data' => 'json',
        'zimra_response' => 'json',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'is_voided' => 'boolean'
    ];

    const TYPE_SALE = 'sale';
    const TYPE_REFUND = 'refund';
    const TYPE_VOID = 'void';
    const TYPE_TRAINING = 'training';

    const PAYMENT_CASH = 'cash';
    const PAYMENT_CARD = 'card';
    const PAYMENT_MOBILE = 'mobile_money';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';

    const STATUS_PENDING = 'pending';
    const STATUS_TRANSMITTED = 'transmitted';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ERROR = 'error';

    public function fiscalDevice()
    {
        return $this->belongsTo(FiscalDevice::class);
    }

    public function receiptItems()
    {
        return $this->hasMany(FiscalReceiptItem::class);
    }

    public function operator()
    {
        return $this->belongsTo(\App\Models\User::class, 'operator_id');
    }

    public function generateQRCode()
    {
        $config = FiscalConfiguration::getFiscalConfig();
        $qrUrl = config('services.zimra.qr_url', 'https://invoice.zimra.co.zw/');
        
        // Format according to ZIMRA specification
        $deviceId = str_pad($this->fiscalDevice->device_id, 10, '0', STR_PAD_LEFT);
        $receiptDate = $this->transaction_date->format('dmY');
        $receiptGlobalNo = str_pad($this->fiscal_number, 10, '0', STR_PAD_LEFT);
        
        // Generate QR data (first 16 characters of MD5 hash from receipt signature)
        $signatureHash = md5($this->digital_signature ?? '');
        $receiptQrData = strtoupper(substr($signatureHash, 0, 16));
        
        $qrCode = $qrUrl . $deviceId . $receiptDate . $receiptGlobalNo . $receiptQrData;
        
        return $qrCode;
    }

    public function generateDigitalSignature()
    {
        // Build signature according to ZIMRA specification
        $signatureData = '';
        $signatureData .= $this->fiscalDevice->device_id;
        $signatureData .= strtoupper($this->receipt_type === 'sale' ? 'FISCALINVOICE' : 
                                   ($this->receipt_type === 'credit_note' ? 'CREDITNOTE' : 'DEBITNOTE'));
        $signatureData .= strtoupper($this->currency_code);
        $signatureData .= $this->fiscal_number;
        $signatureData .= $this->transaction_date->format('Y-m-d\TH:i:s');
        $signatureData .= (int) ($this->total_amount * 100); // Amount in cents
        
        // Add tax information
        $taxGroups = $this->receiptItems->groupBy(function ($item) {
            return $item->tax_id . '_' . $item->tax_rate;
        });
        
        foreach ($taxGroups as $items) {
            $taxCode = $items->first()->tax_code ?? '';
            $taxPercent = $items->first()->tax_rate;
            $taxAmount = $items->sum('tax_amount');
            $salesAmount = $items->sum('line_total');
            
            $signatureData .= $taxCode;
            $signatureData .= number_format($taxPercent, 2, '.', '');
            $signatureData .= (int) ($taxAmount * 100);
            $signatureData .= (int) ($salesAmount * 100);
        }
        
        return hash('sha256', $signatureData);
    }

    public function updateFiscalCounters()
    {
        FiscalCounter::updateCountersForReceipt($this);
    }

    public function getVerificationCode()
    {
        if ($this->verification_code) {
            // Format verification code in groups of 4 separated by dashes
            return chunk_split($this->verification_code, 4, '-');
        }
        return null;
    }

    public function referencedReceipt()
    {
        return $this->belongsTo(FiscalReceipt::class, 'referenced_receipt_id');
    }

    public function creditDebitNotes()
    {
        return $this->hasMany(FiscalReceipt::class, 'referenced_receipt_id');
    }
}
