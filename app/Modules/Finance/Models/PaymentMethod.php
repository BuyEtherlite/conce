<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array'
    ];

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function dailyCollectionsSummaries(): HasMany
    {
        return $this->hasMany(DailyCollectionsSummary::class);
    }

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('name')->get();
    }
}
