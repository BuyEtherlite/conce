<?php

namespace App\Modules\Committee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeResolution extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'committee_resolutions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'committee_id',
        'meeting_id',
        'resolution_number',
        'title',
        'description',
        'resolution_text',
        'status',
        'votes_for',
        'votes_against',
        'abstentions',
        'resolution_date',
        'implementation_date',
        'responsible_person',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'votes_for' => 'integer',
        'votes_against' => 'integer',
        'abstentions' => 'integer',
        'resolution_date' => 'date',
        'implementation_date' => 'date',
    ];

    /**
     * Get the committee that owns the resolution.
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * Get the meeting that owns the resolution.
     */
    public function meeting()
    {
        return $this->belongsTo(CommitteeMeeting::class, 'meeting_id');
    }
}
