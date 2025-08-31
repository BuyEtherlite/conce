<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['customer_code', 'first_name', 'last_name', 'company_name', 'email', 'phone', 'mobile', 'address_line_1', 'address_line_2', 'city', 'state', 'postal_code', 'country', 'tax_number', 'customer_type', 'credit_limit', 'payment_terms_days', 'is_active', 'notes', 'council_id', 'department_id', 'office_id', 'created_by', 'customer_number', 'title', 'id_number', 'alternative_phone', 'physical_address', 'postal_address', 'gender', 'date_of_birth', 'nationality', 'occupation', 'employer', 'monthly_income', 'status', 'updated_by'];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_COMPANY = 'company';
    const TYPE_GOVERNMENT = 'government';

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function arInvoices()
    {
        return $this->hasMany(ArInvoice::class);
    }

    public function arReceipts()
    {
        return $this->hasMany(ArReceipt::class);
    }

    public function invoices()
    {
        return $this->hasMany(ArInvoice::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->customer_type === self::TYPE_COMPANY) {
            return $this->company_name;
        }

        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getDisplayNameAttribute()
    {
        return $this->company_name ?: $this->full_name;
    }

    public function getTotalOutstandingAttribute()
    {
        return $this->arInvoices()
            ->whereIn('status', ['sent', 'overdue'])
            ->sum('balance_due');
    }

    public function getOverdueAmountAttribute()
    {
        return $this->arInvoices()
            ->where('status', 'overdue')
            ->sum('balance_due');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('customer_type', $type);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->customer_code) {
                $customer->customer_code = static::generateCustomerCode();
            }
        });
    }

    public static function generateCustomerCode()
    {
        $prefix = 'CUST';
        $latestCustomer = static::where('customer_code', 'like', $prefix . '%')
            ->orderBy('customer_code', 'desc')
            ->first();

        if (!$latestCustomer) {
            return $prefix . '001';
        }

        $number = (int) substr($latestCustomer->customer_code, strlen($prefix));
        return $prefix . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }
}