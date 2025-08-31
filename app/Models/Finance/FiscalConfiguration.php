<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FiscalConfiguration extends Model
{
    protected $fillable = [
        'operator_tin',
        'operator_name',
        'business_name',
        'business_address',
        'vat_number',
        'currency_code',
        'tax_inclusive',
        'receipt_footer_text',
        'zimra_api_url',
        'zimra_timeout',
        'auto_transmit',
        'backup_enabled',
        'configuration_data'
    ];

    protected $casts = [
        'tax_inclusive' => 'boolean',
        'auto_transmit' => 'boolean',
        'backup_enabled' => 'boolean',
        'configuration_data' => 'json'
    ];

    public static function getFiscalConfig()
    {
        return static::first() ?? new static();
    }
}
