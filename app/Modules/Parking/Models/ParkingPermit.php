<?php

namespace App\Modules\Parking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingPermit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'permit_number',
        'zone_id',
        'permit_type',
        'vehicle_registration',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'holder_name',
        'holder_address',
        'holder_phone',
        'holder_email',
        'issue_date',
        'expiry_date',
        'fee_amount',
        'issued_by',
        'status',
        'notes'
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'issue_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function getPermitTypeLabel(): string
    {
        $labels = [
            'residential' => 'Residential',
            'business' => 'Business',
            'visitor' => 'Visitor',
            'disabled' => 'Disabled',
            'temporary' => 'Temporary'
        ];

        return $labels[$this->permit_type] ?? $this->permit_type;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permit) {
            if (!$permit->permit_number) {
                $permit->permit_number = 'PM-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}