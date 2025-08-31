<?php

namespace App\Models\Facilities;

use Illuminate\Database\Eloquent\Model;

class FacilityDocument extends Model
{
    protected $fillable = [
        'facility_id',
        'document_name',
        'document_type',
        'file_path',
        'file_size',
        'uploaded_by',
        'description'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
