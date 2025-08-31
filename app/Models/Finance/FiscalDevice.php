<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FiscalDevice extends Model
{
    protected $fillable = [
        'device_id',
        'device_name',
        'device_type',
        'serial_number',
        'manufacturer',
        'firmware_version',
        'is_active',
        'last_sync',
        'configuration'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sync' => 'datetime',
        'configuration' => 'json'
    ];

    public function fiscalReceipts()
    {
        return $this->hasMany(FiscalReceipt::class);
    }
}
