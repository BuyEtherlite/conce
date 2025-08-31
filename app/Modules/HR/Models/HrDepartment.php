<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class HrDepartment extends Model
{
    use HasFactory;

    protected $table = 'hr_departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'location',
        'budget',
        'is_active'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function positions()
    {
        return $this->hasMany(HrJobPosition::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(HrEmployee::class, 'department_id');
    }

    public function getActiveEmployeesCountAttribute()
    {
        return $this->employees()->where('employment_status', 'active')->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
