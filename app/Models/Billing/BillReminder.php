<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'type',
        'sent_date',
        'method',
        'message'
    ];

    protected $casts = [
        'sent_date' => 'date'
    ];

    public function bill()
    {
        return $this->belongsTo(MunicipalBill::class, 'bill_id');
    }
}
