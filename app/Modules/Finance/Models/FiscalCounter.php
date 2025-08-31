<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiscalCounter extends Model
{
    use SoftDeletes;

    protected $table = 'fiscal_counters';

    protected $fillable = [
        'device_id',
        'counter_type',
        'counter_value',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'counter_value' => 'integer',
    ];

    public function device()
    {
        return $this->belongsTo(\App\Modules\Finance\Models\FiscalDevice::class, 'device_id');
    }
}