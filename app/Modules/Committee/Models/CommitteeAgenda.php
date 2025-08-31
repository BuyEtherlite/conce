<?php

namespace App\Modules\Committee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeAgenda extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'committee_agendas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'committee_id',
        'meeting_id',
        'agenda_item',
        'description',
        'presenter',
        'time_allocated',
        'order_number',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'time_allocated' => 'integer',
        'order_number' => 'integer',
    ];

    /**
     * Get the committee that owns the agenda.
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * Get the meeting that owns the agenda.
     */
    public function meeting()
    {
        return $this->belongsTo(CommitteeMeeting::class, 'meeting_id');
    }
}
