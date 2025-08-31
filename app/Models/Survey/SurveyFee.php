<?php

namespace App\Models\Survey;

use App\Models\Finance\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_project_id',
        'fee_type',
        'description',
        'amount',
        'quantity',
        'total_amount',
        'status',
        'due_date',
        'invoice_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_INVOICED = 'invoiced';
    const STATUS_PAID = 'paid';
    const STATUS_WAIVED = 'waived';

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->total_amount = $model->amount * $model->quantity;
        });
    }

    public function project()
    {
        return $this->belongsTo(SurveyProject::class, 'survey_project_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'invoiced' => 'info',
            'paid' => 'success',
            'waived' => 'secondary'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               !in_array($this->status, ['paid', 'waived']);
    }
}
