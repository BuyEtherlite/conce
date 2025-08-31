<?php

namespace App\Modules\Committee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommitteeMeeting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'committee_meetings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'committee_id',
        'meeting_date',
        'meeting_time',
        'venue',
        'agenda',
        'status',
        'meeting_type',
        'chairperson_id',
        'secretary_id',
        'quorum_required',
        'quorum_present',
        'minutes',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meeting_date' => 'date',
        'meeting_time' => 'datetime',
        'quorum_required' => 'integer',
        'quorum_present' => 'integer',
    ];

    /**
     * Get the committee that owns the meeting.
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * Get the chairperson of the meeting.
     */
    public function chairperson()
    {
        return $this->belongsTo(\App\Models\User::class, 'chairperson_id');
    }

    /**
     * Get the secretary of the meeting.
     */
    public function secretary()
    {
        return $this->belongsTo(\App\Models\User::class, 'secretary_id');
    }
}
