<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZimbabweChartOfAccount extends Model
{
    use SoftDeletes;

    protected $table = 'zimbabwe_chart_of_accounts';

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'account_category',
        'account_subcategory',
        'account_level',
        'parent_account_code',
        'is_control_account',
        'is_active',
        'government_classification',
        'ipsas_classification',
        'description',
        'opening_balance',
        'current_balance'
    ];

    protected $casts = [
        'is_control_account' => 'boolean',
        'is_active' => 'boolean',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public static function getZimbabweNationalChart()
    {
        return [
            // ASSETS
            ['account_code' => '1000', 'account_name' => 'ASSETS', 'account_type' => 'asset', 'account_category' => 'main', 'account_level' => 1, 'is_control_account' => true],
            ['account_code' => '1100', 'account_name' => 'CURRENT ASSETS', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 2, 'parent_account_code' => '1000'],
            ['account_code' => '1110', 'account_name' => 'Cash and Cash Equivalents', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 3, 'parent_account_code' => '1100'],
            ['account_code' => '1111', 'account_name' => 'Cash on Hand', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1110'],
            ['account_code' => '1112', 'account_name' => 'Bank Accounts - ZWL', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1110'],
            ['account_code' => '1113', 'account_name' => 'Bank Accounts - USD', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1110'],
            ['account_code' => '1120', 'account_name' => 'Accounts Receivable', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 3, 'parent_account_code' => '1100'],
            ['account_code' => '1121', 'account_name' => 'Rates and Taxes Receivable', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1120'],
            ['account_code' => '1122', 'account_name' => 'Water and Sewer Receivable', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1120'],
            ['account_code' => '1123', 'account_name' => 'Refuse Collection Receivable', 'account_type' => 'asset', 'account_category' => 'current_asset', 'account_level' => 4, 'parent_account_code' => '1120'],
            
            // NON-CURRENT ASSETS
            ['account_code' => '1200', 'account_name' => 'NON-CURRENT ASSETS', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 2, 'parent_account_code' => '1000'],
            ['account_code' => '1210', 'account_name' => 'Property, Plant and Equipment', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 3, 'parent_account_code' => '1200'],
            ['account_code' => '1211', 'account_name' => 'Land and Buildings', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 4, 'parent_account_code' => '1210'],
            ['account_code' => '1212', 'account_name' => 'Infrastructure Assets', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 4, 'parent_account_code' => '1210'],
            ['account_code' => '1213', 'account_name' => 'Plant and Equipment', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 4, 'parent_account_code' => '1210'],
            ['account_code' => '1214', 'account_name' => 'Motor Vehicles', 'account_type' => 'asset', 'account_category' => 'non_current_asset', 'account_level' => 4, 'parent_account_code' => '1210'],
            
            // LIABILITIES
            ['account_code' => '2000', 'account_name' => 'LIABILITIES', 'account_type' => 'liability', 'account_category' => 'main', 'account_level' => 1, 'is_control_account' => true],
            ['account_code' => '2100', 'account_name' => 'CURRENT LIABILITIES', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 2, 'parent_account_code' => '2000'],
            ['account_code' => '2110', 'account_name' => 'Accounts Payable', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 3, 'parent_account_code' => '2100'],
            ['account_code' => '2111', 'account_name' => 'Trade Creditors', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 4, 'parent_account_code' => '2110'],
            ['account_code' => '2120', 'account_name' => 'Accrued Expenses', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 3, 'parent_account_code' => '2100'],
            ['account_code' => '2130', 'account_name' => 'Statutory Deductions Payable', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 3, 'parent_account_code' => '2100'],
            ['account_code' => '2131', 'account_name' => 'PAYE Payable', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 4, 'parent_account_code' => '2130'],
            ['account_code' => '2132', 'account_name' => 'NSSA Payable', 'account_type' => 'liability', 'account_category' => 'current_liability', 'account_level' => 4, 'parent_account_code' => '2130'],
            
            // NET ASSETS/EQUITY
            ['account_code' => '3000', 'account_name' => 'NET ASSETS/EQUITY', 'account_type' => 'equity', 'account_category' => 'main', 'account_level' => 1, 'is_control_account' => true],
            ['account_code' => '3100', 'account_name' => 'Accumulated Surplus/Deficit', 'account_type' => 'equity', 'account_category' => 'accumulated_surplus', 'account_level' => 2, 'parent_account_code' => '3000'],
            ['account_code' => '3200', 'account_name' => 'Reserves', 'account_type' => 'equity', 'account_category' => 'reserves', 'account_level' => 2, 'parent_account_code' => '3000'],
            
            // REVENUE
            ['account_code' => '4000', 'account_name' => 'REVENUE', 'account_type' => 'revenue', 'account_category' => 'main', 'account_level' => 1, 'is_control_account' => true],
            ['account_code' => '4100', 'account_name' => 'REVENUE FROM EXCHANGE TRANSACTIONS', 'account_type' => 'revenue', 'account_category' => 'exchange_revenue', 'account_level' => 2, 'parent_account_code' => '4000'],
            ['account_code' => '4110', 'account_name' => 'Service Charges', 'account_type' => 'revenue', 'account_category' => 'exchange_revenue', 'account_level' => 3, 'parent_account_code' => '4100'],
            ['account_code' => '4111', 'account_name' => 'Water and Sewer Charges', 'account_type' => 'revenue', 'account_category' => 'exchange_revenue', 'account_level' => 4, 'parent_account_code' => '4110'],
            ['account_code' => '4112', 'account_name' => 'Refuse Collection Charges', 'account_type' => 'revenue', 'account_category' => 'exchange_revenue', 'account_level' => 4, 'parent_account_code' => '4110'],
            ['account_code' => '4200', 'account_name' => 'REVENUE FROM NON-EXCHANGE TRANSACTIONS', 'account_type' => 'revenue', 'account_category' => 'non_exchange_revenue', 'account_level' => 2, 'parent_account_code' => '4000'],
            ['account_code' => '4210', 'account_name' => 'Property Rates', 'account_type' => 'revenue', 'account_category' => 'non_exchange_revenue', 'account_level' => 3, 'parent_account_code' => '4200'],
            ['account_code' => '4220', 'account_name' => 'Government Grants', 'account_type' => 'revenue', 'account_category' => 'non_exchange_revenue', 'account_level' => 3, 'parent_account_code' => '4200'],
            
            // EXPENSES
            ['account_code' => '5000', 'account_name' => 'EXPENSES', 'account_type' => 'expense', 'account_category' => 'main', 'account_level' => 1, 'is_control_account' => true],
            ['account_code' => '5100', 'account_name' => 'EMPLOYEE COSTS', 'account_type' => 'expense', 'account_category' => 'employee_costs', 'account_level' => 2, 'parent_account_code' => '5000'],
            ['account_code' => '5110', 'account_name' => 'Salaries and Wages', 'account_type' => 'expense', 'account_category' => 'employee_costs', 'account_level' => 3, 'parent_account_code' => '5100'],
            ['account_code' => '5120', 'account_name' => 'Employee Benefits', 'account_type' => 'expense', 'account_category' => 'employee_costs', 'account_level' => 3, 'parent_account_code' => '5100'],
            ['account_code' => '5200', 'account_name' => 'GOODS AND SERVICES', 'account_type' => 'expense', 'account_category' => 'goods_services', 'account_level' => 2, 'parent_account_code' => '5000'],
            ['account_code' => '5210', 'account_name' => 'Professional Services', 'account_type' => 'expense', 'account_category' => 'goods_services', 'account_level' => 3, 'parent_account_code' => '5200'],
            ['account_code' => '5220', 'account_name' => 'Repairs and Maintenance', 'account_type' => 'expense', 'account_category' => 'goods_services', 'account_level' => 3, 'parent_account_code' => '5200'],
            ['account_code' => '5300', 'account_name' => 'DEPRECIATION AND AMORTIZATION', 'account_type' => 'expense', 'account_category' => 'depreciation', 'account_level' => 2, 'parent_account_code' => '5000'],
        ];
    }

    public function parent()
    {
        return $this->belongsTo(ZimbabweChartOfAccount::class, 'parent_account_code', 'account_code');
    }

    public function children()
    {
        return $this->hasMany(ZimbabweChartOfAccount::class, 'parent_account_code', 'account_code');
    }

    public function generalLedgerEntries()
    {
        return $this->hasMany(GeneralLedger::class, 'account_code', 'account_code');
    }
}
