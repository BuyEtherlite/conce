<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosReceipt extends Model
{
    use HasFactory;

    protected $table = 'pos_receipts';

    protected $fillable = [
        'receipt_number',
        'payment_id',
        'total_amount',
        'receipt_date',
        'bills_data',
        'receipt_content',
        'print_status',
        'created_by'
    ];

    protected $casts = [
        'receipt_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'bills_data' => 'array'
    ];

    const PRINT_STATUSES = [
        'pending' => 'Pending',
        'printed' => 'Printed',
        'emailed' => 'Emailed'
    ];

    /**
     * Get the payment for this receipt
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the user who created this receipt
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Generate formatted receipt content
     */
    public function generateReceiptContent()
    {
        $content = "COUNCIL ERP - PAYMENT RECEIPT\n";
        $content .= str_repeat("=", 40) . "\n";
        $content .= "Receipt No: {$this->receipt_number}\n";
        $content .= "Date: " . $this->receipt_date->format('Y-m-d H:i:s') . "\n";
        $content .= "Payment Method: {$this->payment->payment_method_name}\n";
        $content .= "Terminal: {$this->payment->terminal->terminal_name}\n\n";
        
        $content .= "BILLS PAID:\n";
        $content .= str_repeat("-", 40) . "\n";
        
        $total = 0;
        foreach ($this->bills_data as $bill) {
            $content .= "Bill: {$bill['bill_number']}\n";
            $content .= "Customer: {$bill['customer_name']}\n";
            if (isset($bill['meter_number'])) {
                $content .= "Meter: {$bill['meter_number']}\n";
            }
            if (isset($bill['account_number'])) {
                $content .= "Account: {$bill['account_number']}\n";
            }
            $content .= "Amount: $" . number_format($bill['amount'], 2) . "\n\n";
            $total += $bill['amount'];
        }
        
        $content .= str_repeat("-", 40) . "\n";
        $content .= "TOTAL PAID: $" . number_format($total, 2) . "\n";
        $content .= str_repeat("=", 40) . "\n";
        $content .= "Thank you for your payment!\n";
        $content .= "Visit us at: council.gov\n";
        
        $this->receipt_content = $content;
        $this->save();
        
        return $content;
    }

    /**
     * Mark receipt as printed
     */
    public function markAsPrinted()
    {
        $this->print_status = 'printed';
        $this->save();
    }

    /**
     * Mark receipt as emailed
     */
    public function markAsEmailed()
    {
        $this->print_status = 'emailed';
        $this->save();
    }

    /**
     * Get print status display name
     */
    public function getPrintStatusNameAttribute()
    {
        return self::PRINT_STATUSES[$this->print_status] ?? $this->print_status;
    }

    /**
     * Scope for receipts by print status
     */
    public function scopeByPrintStatus($query, $status)
    {
        return $query->where('print_status', $status);
    }

    /**
     * Scope for receipts in date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('receipt_date', [$startDate, $endDate]);
    }

    /**
     * Generate QR code for receipt verification
     */
    public function generateQrCode()
    {
        $qrData = "RECEIPT:{$this->receipt_number}:AMOUNT:{$this->total_amount}:DATE:" . $this->receipt_date->format('Y-m-d');
        return $qrData;
    }
}