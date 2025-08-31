<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_places',
        'exchange_rate',
        'is_base_currency',
        'is_active',
        'auto_update',
        'rounding_precision',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_base_currency' => 'boolean',
        'is_active' => 'boolean',
        'auto_update' => 'boolean',
        'decimal_places' => 'integer',
        'rounding_precision' => 'integer',
        'exchange_rate' => 'decimal:6'
    ];

    /**
     * Get the currency rates for the currency.
     */
    public function rates()
    {
        return $this->hasMany(\App\Modules\HR\Models\CurrencyRate::class);
    }

    /**
     * Get the exchange rate history
     */
    public function exchangeRateHistories()
    {
        return $this->hasMany(ExchangeRateHistory::class);
    }

    /**
     * Get the latest exchange rate history
     */
    public function latestExchangeRate()
    {
        return $this->hasOne(ExchangeRateHistory::class)
            ->where('is_active', true)
            ->orderBy('effective_date', 'desc');
    }

    /**
     * Get the user who created the currency.
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who updated the currency.
     */
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for base currency
     */
    public function scopeBase($query)
    {
        return $query->where('is_base_currency', true);
    }

    /**
     * Scope for auto-update currencies
     */
    public function scopeAutoUpdate($query)
    {
        return $query->where('auto_update', true);
    }

    /**
     * Get current exchange rate (from history or direct)
     */
    public function getCurrentExchangeRate()
    {
        if ($this->is_base_currency) {
            return 1.0;
        }

        $latestRate = $this->latestExchangeRate;
        return $latestRate ? $latestRate->exchange_rate : $this->exchange_rate;
    }

    /**
     * Convert amount to this currency from base currency
     */
    public function convertFromBase($amount)
    {
        return round($amount * $this->getCurrentExchangeRate(), $this->rounding_precision ?? 2);
    }

    /**
     * Convert amount from this currency to base currency
     */
    public function convertToBase($amount)
    {
        if ($this->is_base_currency) {
            return $amount;
        }

        return round($amount / $this->getCurrentExchangeRate(), $this->rounding_precision ?? 2);
    }

    /**
     * Convert amount to another currency
     */
    public function convertTo($amount, Currency $targetCurrency)
    {
        // Convert to base first, then to target
        $baseAmount = $this->convertToBase($amount);
        return $targetCurrency->convertFromBase($baseAmount);
    }

    /**
     * Get formatted amount with currency symbol
     */
    public function formatAmount($amount)
    {
        $precision = $this->rounding_precision ?? 2;
        $formattedAmount = number_format($amount, $precision);
        
        return $this->symbol . ' ' . $formattedAmount;
    }

    /**
     * Get the base currency
     */
    public static function getBaseCurrency()
    {
        return Cache::remember('base_currency', 3600, function() {
            return static::where('is_base_currency', true)->first();
        });
    }

    /**
     * Get currency by code
     */
    public static function findByCode($code)
    {
        return Cache::remember("currency_code_{$code}", 3600, function() use ($code) {
            return static::where('code', $code)->first();
        });
    }

    /**
     * Check if currency has transactions
     */
    public function hasTransactions()
    {
        // Check if this currency is used in any transactions
        $tables = [
            'finance_ar_invoices' => 'currency_code',
            'finance_payments' => 'currency_code',
            'cashbook_entries' => 'currency_code',
            'fdms_receipts' => 'currency_code'
        ];

        foreach ($tables as $table => $column) {
            try {
                $count = \DB::table($table)->where($column, $this->code)->count();
                if ($count > 0) {
                    return true;
                }
            } catch (\Exception $e) {
                // Table might not exist, continue
            }
        }

        return false;
    }

    /**
     * Get exchange rate trend (up, down, stable)
     */
    public function getExchangeRateTrend($days = 7)
    {
        $history = $this->exchangeRateHistories()
            ->where('effective_date', '>=', now()->subDays($days))
            ->orderBy('effective_date', 'asc')
            ->get();

        if ($history->count() < 2) {
            return 'stable';
        }

        $first = $history->first()->exchange_rate;
        $last = $history->last()->exchange_rate;

        $change = (($last - $first) / $first) * 100;

        if ($change > 1) return 'up';
        if ($change < -1) return 'down';
        return 'stable';
    }

    /**
     * Get exchange rate change percentage
     */
    public function getExchangeRateChange($days = 7)
    {
        $history = $this->exchangeRateHistories()
            ->where('effective_date', '>=', now()->subDays($days))
            ->orderBy('effective_date', 'asc')
            ->get();

        if ($history->count() < 2) {
            return 0;
        }

        $first = $history->first()->exchange_rate;
        $last = $history->last()->exchange_rate;

        return round((($last - $first) / $first) * 100, 2);
    }

    /**
     * Update exchange rate with history
     */
    public function updateExchangeRate($newRate, $source = 'manual', $effectiveDate = null)
    {
        $effectiveDate = $effectiveDate ?: now();
        
        // Don't update if rate hasn't changed significantly
        if (abs($this->exchange_rate - $newRate) < 0.0001) {
            return false;
        }

        \DB::transaction(function() use ($newRate, $source, $effectiveDate) {
            // Update the currency
            $this->update(['exchange_rate' => $newRate]);

            // Create history record
            ExchangeRateHistory::create([
                'currency_id' => $this->id,
                'exchange_rate' => $newRate,
                'effective_date' => $effectiveDate,
                'source' => $source,
                'is_active' => true,
                'created_by' => auth()->id()
            ]);
        });

        // Clear cache
        Cache::forget("currency_code_{$this->code}");
        Cache::forget('base_currency');

        return true;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($currency) {
            if (!$currency->created_by) {
                $currency->created_by = auth()->id();
            }
            
            // Set default rounding precision
            if (!$currency->rounding_precision) {
                $currency->rounding_precision = 2;
            }
        });

        static::updating(function ($currency) {
            $currency->updated_by = auth()->id();
        });

        static::saved(function ($currency) {
            // Clear cache when currency is saved
            Cache::forget("currency_code_{$currency->code}");
            Cache::forget('base_currency');
        });
    }
}