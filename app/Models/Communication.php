<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use HasFactory;

    protected $fillable = [
        'communication_number',
        'customer_id',
        'type',
        'subject',
        'content',
        'direction',
        'status',
        'sent_at',
        'delivered_at',
        'read_at',
        'sender_name',
        'sender_email',
        'sender_phone',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'attachments',
        'service_request_id',
        'created_by',
        'council_id',
        'department_id'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'attachments' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($communication) {
            if (empty($communication->communication_number)) {
                $communication->communication_number = self::generateCommunicationNumber();
            }
        });
    }

    public static function generateCommunicationNumber()
    {
        $year = date('Y');
        $lastCommunication = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastCommunication ? intval(substr($lastCommunication->communication_number, -4)) + 1 : 1;
        
        return 'COMM' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
