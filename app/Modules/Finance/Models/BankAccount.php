<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_name',
        'bank_name',
        'branch_name',
        'account_code',
        'currency_code',
        'opening_balance',
        'current_balance',
        'is_active'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function chartAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_code', 'account_code');
    }

    public function cashbookEntries()
    {
        return $this->hasMany(CashbookEntry::class);
    }

    public function bankReconciliations()
    {
        return $this->hasMany(BankReconciliation::class);
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->current_balance, 2);
    }

    public function updateBalance($amount, $type = 'add')
    {
        if ($type === 'add') {
            $this->current_balance += $amount;
        } else {
            $this->current_balance -= $amount;
        }
        
        $this->save();
        
        // Update linked chart account
        if ($this->chartAccount) {
            $this->chartAccount->current_balance = $this->current_balance;
            $this->chartAccount->save();
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
