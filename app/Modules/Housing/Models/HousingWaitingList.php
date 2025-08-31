<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingWaitingList extends Model
{
    use HasFactory;

    protected $table = 'housing_waiting_lists';

    protected $fillable = [
        'application_id',
        'property_type',
        'preferred_area',
        'priority_score',
        'priority_reasons',
        'date_added',
        'position_in_queue',
        'status',
        'last_contact_date',
        'contact_notes',
    ];

    protected $casts = [
        'date_added' => 'date',
        'last_contact_date' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(HousingApplication::class, 'application_id');
    }
}
