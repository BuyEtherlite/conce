<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class GeneralLedger extends Model
{
    use HasFactory;

    protected $table = 'finance_general_ledger';

    protected $fillable = [
        'transaction_number',
        'account_code',
        'transaction_date',
        'transaction_type',
        'amount',
        'description',
        'reference_number',
        'source_document',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_code', 'account_code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public static function generateTransactionNumber()
    {
        $prefix = 'TXN';
        $date = now()->format('Ymd');
        $sequence = static::whereDate('created_at', now())->count() + 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function createEntry($data)
    {
        $entry = new static();
        $entry->fill($data);

        if (empty($entry->transaction_number)) {
            $entry->transaction_number = static::generateTransactionNumber();
        }

        $entry->save();

        // Update account balance
        if ($entry->account) {
            $entry->account->updateBalance($entry->amount, $entry->transaction_type);
        }

        return $entry;
    }

    public function scopeByAccount($query, $accountCode)
    {
        return $query->where('account_code', $accountCode);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }
}