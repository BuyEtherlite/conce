<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'address',
        'phone',
        'email',
        'office_type',
        'capacity',
        'facilities',
        'council_id',
        'department_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'facilities' => 'array',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function properties()
    {
        return $this->hasMany(\App\Models\Housing\Property::class);
    }

    public function housingApplications()
    {
        return $this->hasMany(\App\Models\Housing\HousingApplication::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Finance\Invoice::class);
    }
}