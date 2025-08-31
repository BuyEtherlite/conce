<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules;

class ChartOfAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finance_chart_of_accounts';

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'account_subtype',
        'description',
        'opening_balance',
        'current_balance',
        'is_active',
        'parent_id',
        'council_id'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function generalLedgerEntries()
    {
        return $this->hasMany(GeneralLedger::class, 'account_code', 'account_code');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'account_code', 'account_code');
    }

    public function getBalanceAttribute()
    {
        return $this->current_balance;
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->current_balance, 2);
    }

    public function isDebitAccount()
    {
        return in_array($this->account_type, ['asset', 'expense']);
    }

    public function isCreditAccount()
    {
        return in_array($this->account_type, ['liability', 'equity', 'revenue']);
    }

    public function updateBalance($amount, $type)
    {
        if ($type === 'debit') {
            if ($this->isDebitAccount()) {
                $this->current_balance += $amount;
            } else {
                $this->current_balance -= $amount;
            }
        } else { // credit
            if ($this->isCreditAccount()) {
                $this->current_balance += $amount;
            } else {
                $this->current_balance -= $amount;
            }
        }
        
        $this->save();
    }

    public static function getAccountTypes()
    {
        return [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'revenue' => 'Revenue',
            'expense' => 'Expense'
        ];
    }

    public static function getAccountSubtypes()
    {
        return [
            'current_asset' => 'Current Asset',
            'fixed_asset' => 'Fixed Asset',
            'current_liability' => 'Current Liability',
            'long_term_liability' => 'Long-term Liability',
            'equity' => 'Equity',
            'income' => 'Income',
            'cost_of_goods_sold' => 'Cost of Goods Sold',
            'expense' => 'Expense',
            'other_income' => 'Other Income',
            'other_expense' => 'Other Expense'
        ];
    }
}
