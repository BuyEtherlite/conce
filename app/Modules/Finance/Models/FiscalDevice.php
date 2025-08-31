<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiscalDevice extends Model
{
    use SoftDeletes;

    protected $table = 'fiscal_devices';

    protected $fillable = [
        'device_name',
        'serial_number',
        'device_type',
        'status',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function counters()
    {
        return $this->hasMany(\App\Modules\Finance\Models\FiscalCounter::class, 'device_id');
    }

    public function receipts()
    {
        return $this->hasMany(\App\Modules\Finance\Models\FiscalReceipt::class, 'device_id');
    }
}