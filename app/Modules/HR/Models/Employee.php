<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Department;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'national_id',
        'hire_date',
        'termination_date',
        'status',
        'job_title',
        'department_id',
        'basic_salary',
        'basic_salary_currency',
        'allowances',
        'allowances_currency',
        'salary_breakdown',
        'bank_account',
        'bank_name',
        'face_encoding_file',
        'face_enrolled',
        'face_enrolled_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'salary_breakdown' => 'array',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'face_enrolled' => 'boolean',
        'face_enrolled_at' => 'datetime'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function faceEnrollments(): HasMany
    {
        return $this->hasMany(FaceEnrollment::class);
    }

    public function salaryAdjustments(): HasMany
    {
        return $this->hasMany(SalaryAdjustment::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTotalSalaryInCurrency(string $currency = 'USD'): array
    {
        $basic = $this->convertCurrency($this->basic_salary, $this->basic_salary_currency, $currency);
        $allowances = $this->convertCurrency($this->allowances, $this->allowances_currency, $currency);
        
        return [
            'basic_salary' => $basic,
            'allowances' => $allowances,
            'total' => $basic + $allowances,
            'currency' => $currency
        ];
    }

    public function getSalaryBreakdownAttribute(): array
    {
        $breakdown = json_decode($this->attributes['salary_breakdown'] ?? '[]', true);
        
        if (empty($breakdown)) {
            return [
                [
                    'component' => 'Basic Salary',
                    'amount' => $this->basic_salary,
                    'currency' => $this->basic_salary_currency,
                    'percentage' => 100
                ]
            ];
        }
        
        return $breakdown;
    }

    private function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = CurrencyRate::where('from_currency', $fromCurrency)
                           ->where('to_currency', $toCurrency)
                           ->where('is_active', true)
                           ->latest('rate_date')
                           ->first();

        return $rate ? $amount * $rate->rate : $amount;
    }

    public function hasActiveFaceEnrollment(): bool
    {
        return $this->face_enrolled && 
               $this->faceEnrollments()
                    ->where('status', 'active')
                    ->where(function($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    })
                    ->exists();
    }
}
