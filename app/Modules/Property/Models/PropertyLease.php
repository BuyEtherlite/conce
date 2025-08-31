<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PropertyLease extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_number',
        'property_id',
        'tenant_name',
        'tenant_id_number',
        'tenant_email',
        'tenant_phone',
        'tenant_address',
        'lease_start_date',
        'lease_end_date',
        'monthly_rental',
        'deposit_amount',
        'annual_escalation',
        'payment_frequency',
        'payment_day',
        'lease_type',
        'special_conditions',
        'status',
        'notice_period_days',
        'documents',
        'managed_by'
    ];

    protected $casts = [
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'monthly_rental' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'annual_escalation' => 'decimal:2',
        'documents' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where('lease_start_date', '<=', now())
            ->where('lease_end_date', '>=', now());
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->lease_start_date <= now() && 
               $this->lease_end_date >= now();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lease) {
            if (empty($lease->lease_number)) {
                $lease->lease_number = 'LSE' . date('Y') . str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
