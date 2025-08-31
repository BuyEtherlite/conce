<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finance\Currency;
use App\Models\User;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'exchange_rate',
        'effective_date',
        'rate_type',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('effective_date', 'desc');
    }

    public function scopeForCurrency($query, $currencyId)
    {
        return $query->where('currency_id', $currencyId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
