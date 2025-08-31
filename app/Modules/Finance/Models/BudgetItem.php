<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    protected $table = 'budget_items';

    protected $fillable = [
        'budget_id',
        'account_code',
        'description',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'quarter_1',
        'quarter_2',
        'quarter_3',
        'quarter_4'
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'quarter_1' => 'decimal:2',
        'quarter_2' => 'decimal:2',
        'quarter_3' => 'decimal:2',
        'quarter_4' => 'decimal:2'
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_code', 'account_code');
    }
}
