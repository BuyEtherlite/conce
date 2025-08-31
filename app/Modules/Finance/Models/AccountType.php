<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'rate_per_unit',
        'unit_type',
        'is_active'
    ];

    protected $casts = [
        'rate_per_unit' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    public function customerAccounts(): HasMany
    {
        return $this->hasMany(CustomerAccount::class);
    }

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('name')->get();
    }
}
