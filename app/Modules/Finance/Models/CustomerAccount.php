<?php

namespace App\Modules\Finance\Models;

use App\Modules;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerAccount extends Model
{
    protected $fillable = [
        'account_number',
        'customer_id',
        'account_type_id',
        'meter_number',
        'current_balance',
        'previous_balance',
        'last_meter_reading_date',
        'last_meter_reading',
        'current_meter_reading',
        'is_active'
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'last_meter_reading' => 'decimal:2',
        'current_meter_reading' => 'decimal:2',
        'last_meter_reading_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function meterReadings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function updateBalance($amount, $operation = 'debit')
    {
        $this->previous_balance = $this->current_balance;
        
        if ($operation === 'debit') {
            $this->current_balance += $amount;
        } else {
            $this->current_balance -= $amount;
        }
        
        $this->save();
    }

    public function getCurrentOwing()
    {
        return $this->current_balance > 0 ? $this->current_balance : 0;
    }
}
