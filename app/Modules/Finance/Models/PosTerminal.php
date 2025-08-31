<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosTerminal extends Model
{
    use SoftDeletes;

    protected $table = 'pos_terminals';

    protected $fillable = [
        'terminal_name',
        'terminal_id',
        'location',
        'status',
        'ip_address',
        'last_ping',
        'config_settings'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_ping'
    ];

    protected $casts = [
        'last_ping' => 'datetime',
        'config_settings' => 'array'
    ];

    /**
     * Get receipts for this terminal.
     */
    public function receipts()
    {
        return $this->hasMany(PosReceipt::class, 'terminal_id');
    }

    /**
     * Get payments processed by this terminal.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'terminal_id');
    }
}
