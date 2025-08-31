<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'hours_worked',
        'overtime_hours',
        'status',
        'check_in_method',
        'check_out_method',
        'face_confidence_in',
        'face_confidence_out',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'face_confidence_in' => 'decimal:2',
        'face_confidence_out' => 'decimal:2'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateHours(): void
    {
        if ($this->check_in && $this->check_out) {
            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);
            
            $totalHours = $checkOut->diffInMinutes($checkIn) / 60;
            $regularHours = min($totalHours, 8); // Assuming 8-hour work day
            $overtimeHours = max(0, $totalHours - 8);
            
            $this->hours_worked = $regularHours;
            $this->overtime_hours = $overtimeHours;
        }
    }

    public function isLate(): bool
    {
        if (!$this->check_in) return false;
        
        $expectedTime = Carbon::parse($this->attendance_date->format('Y-m-d') . ' 09:00:00');
        return Carbon::parse($this->check_in)->gt($expectedTime);
    }

    public function isFaceScanReliable(): bool
    {
        $minConfidence = 85.0; // Minimum confidence threshold
        
        return ($this->check_in_method === 'face_scan' && $this->face_confidence_in >= $minConfidence) ||
               ($this->check_out_method === 'face_scan' && $this->face_confidence_out >= $minConfidence);
    }
}
