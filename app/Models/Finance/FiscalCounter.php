<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FiscalCounter extends Model
{
    protected $fillable = [
        'fiscal_device_id',
        'counter_type',
        'counter_value',
        'fiscal_day_number',
        'last_updated'
    ];

    protected $casts = [
        'counter_value' => 'integer',
        'fiscal_day_number' => 'integer',
        'last_updated' => 'datetime'
    ];

    public function fiscalDevice()
    {
        return $this->belongsTo(FiscalDevice::class);
    }

    public static function updateCountersForReceipt(FiscalReceipt $receipt)
    {
        // Implementation for updating fiscal counters
        // This would update various counters based on the receipt type
    }
}
