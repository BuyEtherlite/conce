<?php

namespace App\Modules\Health\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthFacility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'facility_type',
        'license_number',
        'owner_name',
        'owner_contact',
        'address',
        'coordinates',
        'capacity',
        'services_offered',
        'operating_hours',
        'contact_person',
        'phone',
        'email',
        'status',
        'registration_date',
        'last_inspection_date',
        'compliance_rating',
        'notes',
        'council_id'
    ];

    protected $casts = [
        'services_offered' => 'array',
        'coordinates' => 'array',
        'registration_date' => 'date',
        'last_inspection_date' => 'date'
    ];

    public function permits()
    {
        return $this->hasMany(HealthPermit::class, 'facility_id');
    }

    public function inspections()
    {
        return $this->hasMany(HealthInspection::class, 'facility_id');
    }

    public function practitioners()
    {
        return $this->hasMany(HealthPractitioner::class, 'facility_id');
    }

    public function getActivePermitsAttribute()
    {
        return $this->permits()->where('status', 'active')->where('expiry_date', '>', now())->count();
    }

    public function getComplianceStatusAttribute()
    {
        if ($this->compliance_rating >= 90) return 'excellent';
        if ($this->compliance_rating >= 80) return 'good';
        if ($this->compliance_rating >= 70) return 'satisfactory';
        return 'needs_improvement';
    }
}
