<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalLine extends Model
{
    use HasFactory;

    protected $table = 'finance_general_journal_lines';

    protected $fillable = [
        'journal_header_id',
        'account_code',
        'description',
        'debit_amount',
        'credit_amount',
        'line_number'
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    public function header()
    {
        return $this->belongsTo(GeneralJournalHeader::class, 'journal_header_id');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_code', 'account_code');
    }

    public function getFormattedDebitAttribute()
    {
        return $this->debit_amount > 0 ? number_format($this->debit_amount, 2) : '';
    }

    public function getFormattedCreditAttribute()
    {
        return $this->credit_amount > 0 ? number_format($this->credit_amount, 2) : '';
    }

    public function getAmountAttribute()
    {
        return max($this->debit_amount, $this->credit_amount);
    }

    public function isDebit()
    {
        return $this->debit_amount > 0;
    }

    public function isCredit()
    {
        return $this->credit_amount > 0;
    }
}