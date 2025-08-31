<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurialRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'deceased_name',
        'date_of_birth',
        'date_of_death',
        'burial_date',
        'age_at_death',
        'cause_of_death',
        'next_of_kin',
        'next_of_kin_relationship',
        'contact_phone',
        'contact_email',
        'burial_notes',
        'funeral_director',
        'burial_fee',
        'payment_status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
        'burial_date' => 'date',
        'burial_fee' => 'decimal:2',
        'payment_status' => 'boolean',
    ];

    public function plot()
    {
        return $this->belongsTo(CemeteryPlot::class, 'plot_id');
    }
}
