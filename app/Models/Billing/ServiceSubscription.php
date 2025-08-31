<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_account_id',
        'service_id',
        'start_date',
        'end_date',
        'is_active',
        'custom_rate'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'custom_rate' => 'decimal:2'
    ];

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function service()
    {
        return $this->belongsTo(MunicipalService::class, 'service_id');
    }
}
