<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'facility_type_id',
        'facility_status_id',
        'parent_facility_id',
        'capacity',
        'hourly_rate',
        'daily_rate',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the facility type that owns the facility.
     */
    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }

    /**
     * Get the facility status that owns the facility.
     */
    public function facilityStatus()
    {
        return $this->belongsTo(FacilityStatus::class);
    }

    /**
     * Get the parent facility.
     */
    public function parentFacility()
    {
        return $this->belongsTo(Facility::class, 'parent_facility_id');
    }

    /**
     * Get the child facilities.
     */
    public function childFacilities()
    {
        return $this->hasMany(Facility::class, 'parent_facility_id');
    }

    /**
     * Get the user who created the facility.
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who updated the facility.
     */
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
