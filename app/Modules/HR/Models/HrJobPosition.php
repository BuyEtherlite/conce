<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrJobPosition extends Model
{
    use HasFactory;

    protected $table = 'hr_job_positions';

    protected $fillable = [
        'title',
        'code',
        'department_id',
        'description',
        'requirements',
        'min_salary',
        'max_salary',
        'employment_type',
        'is_active'
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(HrDepartment::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(HrEmployee::class, 'position_id');
    }

    public function getEmploymentTypeDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->employment_type));
    }

    public function getSalaryRangeAttribute()
    {
        if ($this->min_salary && $this->max_salary) {
            return '$' . number_format($this->min_salary, 2) . ' - $' . number_format($this->max_salary, 2);
        }
        return 'Not specified';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
