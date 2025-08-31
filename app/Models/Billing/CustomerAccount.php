<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Council;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_type',
        'customer_name',
        'contact_person',
        'phone',
        'email',
        'physical_address',
        'postal_address',
        'id_number',
        'vat_number',
        'credit_limit',
        'current_balance',
        'is_active',
        'council_id'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'account_opened' => 'date'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ServiceSubscription::class);
    }

    public function bills()
    {
        return $this->hasMany(MunicipalBill::class);
    }

    public function payments()
    {
        return $this->hasMany(BillPayment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function updateBalance()
    {
        $totalOutstanding = $this->bills()->where('status', '!=', 'paid')->sum('outstanding_amount');
        $this->update(['current_balance' => $totalOutstanding]);
    }

    public function getOutstandingBillsAttribute()
    {
        return $this->bills()->where('status', 'overdue')->count();
    }
}
