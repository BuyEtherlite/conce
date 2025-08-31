<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrAttendance extends Model
{
    use HasFactory;

    protected $table = 'hr_attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'hours_worked',
        'overtime_hours',
        'status',
        'office_id',
        'location_details',
        'ip_address',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
    ];

    public function employee()
    {
        return $this->belongsTo(HrEmployee::class, 'employee_id');
    }

    public function office()
    {
        return $this->belongsTo(\App\Models\Office::class, 'office_id');
    }

    public function getStatusDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getTotalHoursAttribute()
    {
        return $this->hours_worked + $this->overtime_hours;
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
