<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestEscalation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'escalated_from',
        'escalated_to',
        'reason',
        'escalated_by',
        'escalated_at'
    ];

    protected $casts = [
        'escalated_at' => 'datetime'
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function escalatedFrom()
    {
        return $this->belongsTo(User::class, 'escalated_from');
    }

    public function escalatedTo()
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    public function escalatedBy()
    {
        return $this->belongsTo(User::class, 'escalated_by');
    }
}

