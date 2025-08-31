<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingInspection extends Model
{
    use HasFactory;

    protected $table = 'housing_inspections';

    protected $fillable = [
        'inspection_number',
        'property_id',
        'inspection_type',
        'inspection_date',
        'inspection_time',
        'inspector_id',
        'status',
        'checklist_items',
        'inspection_results',
        'overall_condition',
        'findings',
        'issues_identified',
        'recommendations',
        'photos',
        'tenant_present',
        'tenant_signature',
        'next_inspection_due',
        'follow_up_actions',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'time',
        'checklist_items' => 'json',
        'inspection_results' => 'json',
        'issues_identified' => 'json',
        'photos' => 'json',
        'tenant_present' => 'boolean',
        'next_inspection_due' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(HousingProperty::class, 'property_id');
    }
}
