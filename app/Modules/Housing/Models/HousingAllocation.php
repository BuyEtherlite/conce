<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousingAllocation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'housing_allocations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'allocation_number',
        'customer_id',
        'property_id',
        'application_id',
        'allocation_date',
        'move_in_date',
        'lease_start_date',
        'lease_end_date',
        'monthly_rent',
        'deposit_paid',
        'status',
        'notes',
        'allocated_by',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'allocation_date' => 'date',
        'move_in_date' => 'date',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit_paid' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the allocation.
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    /**
     * Get the property that owns the allocation.
     */
    public function property()
    {
        return $this->belongsTo(HousingProperty::class);
    }

    /**
     * Get the application that led to this allocation.
     */
    public function application()
    {
        return $this->belongsTo(HousingApplication::class);
    }

    /**
     * Get the user who allocated the property.
     */
    public function allocatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'allocated_by');
    }
}
