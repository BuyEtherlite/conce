<?php

namespace App\Modules\Licensing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules;


class LicenseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'license_type_id',
        'business_name',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'business_address',
        'business_description',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'rejection_reason',
        'notes',
        'council_id',
        'created_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function licenseType()
    {
        return $this->belongsTo(LicenseType::class);
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function documents()
    {
        return $this->hasMany(LicenseDocument::class, 'application_id');
    }

    public function inspections()
    {
        return $this->hasMany(LicenseInspection::class, 'application_id');
    }

    public function license()
    {
        return $this->hasOne(BusinessLicense::class, 'application_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'under_review' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'requires_inspection' => 'primary',
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}
