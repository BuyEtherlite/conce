<?php

namespace App\Modules\Markets\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketMaintenanceRequest extends Model
{
    protected $fillable = [
        'request_number',
        'market_id',
        'market_stall_id',
        'requested_by_name',
        'requested_by_phone',
        'request_type',
        'priority',
        'title',
        'description',
        'estimated_cost',
        'requested_date',
        'scheduled_date',
        'completed_date',
        'status',
        'work_performed',
        'actual_cost',
        'contractor_name',
        'before_photos',
        'after_photos',
        'assigned_to'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'requested_date' => 'date',
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'before_photos' => 'array',
        'after_photos' => 'array',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function stall(): BelongsTo
    {
        return $this->belongsTo(MarketStall::class, 'market_stall_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (!$request->request_number) {
                $request->request_number = 'MNT-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}