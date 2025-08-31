<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalConfiguration extends Model
{
    protected $fillable = [
        'is_fiscalization_enabled',
        'company_tin',
        'company_name',
        'company_address',
        'tax_office_code',
        'business_license_number',
        'vat_registration_number',
        'fiscal_year_start',
        'default_tax_rate',
        'currency_code',
        'receipt_header_text',
        'receipt_footer_text',
        'require_customer_details',
        'auto_transmit_to_zimra',
        'backup_frequency',
        'configuration_data'
    ];

    protected $casts = [
        'is_fiscalization_enabled' => 'boolean',
        'fiscal_year_start' => 'date',
        'default_tax_rate' => 'decimal:2',
        'require_customer_details' => 'boolean',
        'auto_transmit_to_zimra' => 'boolean',
        'configuration_data' => 'json'
    ];

    public static function getFiscalConfig()
    {
        return self::first() ?? self::create([
            'is_fiscalization_enabled' => false,
            'company_name' => config('app.name', 'Municipal Council'),
            'currency_code' => 'USD',
            'default_tax_rate' => 15.00,
            'fiscal_year_start' => now()->startOfYear(),
            'require_customer_details' => false,
            'auto_transmit_to_zimra' => true
        ]);
    }

    public function isFiscalizationEnabled()
    {
        return $this->is_fiscalization_enabled;
    }
}
