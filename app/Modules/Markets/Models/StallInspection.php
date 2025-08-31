<?php

namespace App\Modules\Markets\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StallInspection extends Model
{
    protected $fillable = [
        'inspection_number',
        'market_stall_id',
        'stall_allocation_id',
        'inspection_date',
        'inspector_name',
        'inspection_type',
        'overall_condition',
        'checklist_items',
        'findings',
        'recommendations',
        'violations',
        'follow_up_date',
        'status',
        'photos',
        'inspected_by'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'follow_up_date' => 'date',
        'checklist_items' => 'array',
        'photos' => 'array',
    ];

    public function stall(): BelongsTo
    {
        return $this->belongsTo(MarketStall::class, 'market_stall_id');
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(StallAllocation::class, 'stall_allocation_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inspection) {
            if (!$inspection->inspection_number) {
                $inspection->inspection_number = 'INS-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}