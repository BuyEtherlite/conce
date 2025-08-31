<?php

namespace App\Modules\Health\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthPractitioner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'profession',
        'registration_number',
        'license_number',
        'qualification',
        'institution',
        'graduation_year',
        'specialization',
        'facility_id',
        'employment_type',
        'start_date',
        'end_date',
        'status',
        'phone',
        'email',
        'address',
        'emergency_contact',
        'license_expiry_date',
        'continuing_education_hours',
        'disciplinary_actions',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'license_expiry_date' => 'date',
        'disciplinary_actions' => 'array'
    ];

    public function facility()
    {
        return $this->belongsTo(HealthFacility::class, 'facility_id');
    }

    public function getIsLicenseValidAttribute()
    {
        return $this->license_expiry_date > now();
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'active': return 'success';
            case 'inactive': return 'secondary';
            case 'suspended': return 'warning';
            case 'terminated': return 'danger';
            default: return 'primary';
        }
    }
}
