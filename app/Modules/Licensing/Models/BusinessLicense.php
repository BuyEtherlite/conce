<?php

namespace App\Modules\Licensing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessLicense extends Model
{
    use SoftDeletes;

    protected $table = 'business_licenses';

    protected $fillable = [
        'license_number',
        'business_name',
        'business_type',
        'owner_name',
        'owner_id_number',
        'owner_contact',
        'owner_email',
        'business_address',
        'postal_address',
        'ward_id',
        'registration_number',
        'tax_number',
        'license_type_id',
        'application_date',
        'issue_date',
        'expiry_date',
        'status',
        'fee_amount',
        'fee_paid',
        'payment_status',
        'conditions',
        'remarks',
        'processed_by',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'is_renewable',
        'is_active'
    ];

    protected $dates = [
        'application_date',
        'issue_date',
        'expiry_date',
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'application_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
        'fee_amount' => 'decimal:2',
        'fee_paid' => 'decimal:2',
        'conditions' => 'array',
        'is_renewable' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function licenseType(): BelongsTo
    {
        return $this->belongsTo(LicenseType::class, 'license_type_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'processed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(LicenseApplication::class);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'expired' => 'Expired',
            'suspended' => 'Suspended',
            'cancelled' => 'Cancelled'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'expired' => 'badge-dark',
            'suspended' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            default => 'badge-light'
        };
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function getIsExpiringSoonAttribute()
    {
        return $this->expiry_date && $this->expiry_date <= now()->addDays(30);
    }

    public function getOutstandingBalanceAttribute()
    {
        return $this->fee_amount - $this->fee_paid;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('status', 'approved')
                    ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Methods
    public function approve($userId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId ?? auth()->id();
        $this->approved_at = now();
        $this->is_active = true;
        $this->save();

        return $this;
    }

    public function reject($reason = null, $userId = null)
    {
        $this->status = 'rejected';
        $this->rejected_reason = $reason;
        $this->processed_by = $userId ?? auth()->id();
        $this->is_active = false;
        $this->save();

        return $this;
    }

    public function suspend($reason = null)
    {
        $this->status = 'suspended';
        $this->remarks = $reason;
        $this->is_active = false;
        $this->save();

        return $this;
    }

    public function activate()
    {
        $this->status = 'approved';
        $this->is_active = true;
        $this->save();

        return $this;
    }

    public function renew($newExpiryDate)
    {
        if (!$this->is_renewable) {
            throw new \Exception('This license is not renewable');
        }

        $this->expiry_date = $newExpiryDate;
        $this->status = 'approved';
        $this->is_active = true;
        $this->save();

        return $this;
    }

    public function generateLicenseNumber()
    {
        $year = date('Y');
        $prefix = 'BL';
        
        $lastLicense = static::whereRaw('license_number LIKE ?', ["{$prefix}{$year}%"])
                            ->orderBy('license_number', 'desc')
                            ->first();

        if ($lastLicense) {
            $lastNumber = intval(substr($lastLicense->license_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
