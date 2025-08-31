<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRateHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'exchange_rate',
        'effective_date',
        'source',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'effective_date' => 'datetime',
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean'
    ];

    const SOURCES = [
        'manual' => 'Manual Entry',
        'api' => 'API Update',
        'import' => 'File Import',
        'system' => 'System Generated'
    ];

    /**
     * Get the currency
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the user who created this entry
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get source display name
     */
    public function getSourceNameAttribute()
    {
        return self::SOURCES[$this->source] ?? $this->source;
    }

    /**
     * Scope for active rates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('effective_date', '<=', $date)
                    ->orderBy('effective_date', 'desc');
    }

    /**
     * Scope for specific currency
     */
    public function scopeForCurrency($query, $currencyId)
    {
        return $query->where('currency_id', $currencyId);
    }

    /**
     * Get rate change percentage from previous
     */
    public function getRateChangeAttribute()
    {
        $previousRate = static::where('currency_id', $this->currency_id)
            ->where('effective_date', '<', $this->effective_date)
            ->orderBy('effective_date', 'desc')
            ->first();

        if (!$previousRate || $previousRate->exchange_rate == 0) {
            return 0;
        }

        return (($this->exchange_rate - $previousRate->exchange_rate) / $previousRate->exchange_rate) * 100;
    }

    /**
     * Check if rate increased
     */
    public function isRateIncrease()
    {
        return $this->rate_change > 0;
    }

    /**
     * Check if rate decreased
     */
    public function isRateDecrease()
    {
        return $this->rate_change < 0;
    }

    /**
     * Get the latest rate for a currency on a specific date
     */
    public static function getLatestRateForDate($currencyId, $date)
    {
        return static::forCurrency($currencyId)
            ->forDate($date)
            ->active()
            ->first();
    }

    /**
     * Create new rate entry and deactivate previous
     */
    public static function createNewRate($currencyId, $rate, $effectiveDate, $source = 'manual', $createdBy = null)
    {
        DB::transaction(function() use ($currencyId, $rate, $effectiveDate, $source, $createdBy) {
            // Deactivate previous rates for this currency
            static::where('currency_id', $currencyId)
                ->where('effective_date', '<=', $effectiveDate)
                ->update(['is_active' => false]);

            // Create new rate
            return static::create([
                'currency_id' => $currencyId,
                'exchange_rate' => $rate,
                'effective_date' => $effectiveDate,
                'source' => $source,
                'is_active' => true,
                'created_by' => $createdBy
            ]);
        });
    }
}