<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class HrEmployee extends Model
{
    use HasFactory;

    protected $table = 'hr_employees';

    protected $fillable = [
        'employee_number',
        'user_id',
        'department_id',
        'position_id',
        'manager_id',
        'hire_date',
        'probation_end_date',
        'employment_status',
        'employment_type',
        'base_salary',
        'work_location',
        'emergency_contacts',
        'qualifications'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'probation_end_date' => 'date',
        'base_salary' => 'decimal:2',
        'emergency_contacts' => 'array',
        'qualifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(HrDepartment::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(HrJobPosition::class, 'position_id');
    }

    public function manager()
    {
        return $this->belongsTo(HrEmployee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(HrEmployee::class, 'manager_id');
    }

    public function attendance()
    {
        return $this->hasMany(HrAttendance::class, 'employee_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(HrLeaveRequest::class, 'employee_id');
    }

    public function payroll()
    {
        return $this->hasMany(HrPayroll::class, 'employee_id');
    }

    public function performanceReviews()
    {
        return $this->hasMany(HrPerformanceReview::class, 'employee_id');
    }

    public function training()
    {
        return $this->hasMany(HrEmployeeTraining::class, 'employee_id');
    }

    public function benefits()
    {
        return $this->hasMany(HrEmployeeBenefit::class, 'employee_id');
    }

    public function documents()
    {
        return $this->hasMany(HrDocument::class, 'employee_id');
    }

    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date->diffInYears(now());
    }

    public function getEmploymentStatusDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->employment_status));
    }

    public function getEmploymentTypeDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->employment_type));
    }

    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}
