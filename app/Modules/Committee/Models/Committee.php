<?php

namespace App\Modules\Committee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'committees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'committee_type',
        'meeting_frequency',
        'chairperson_id',
        'secretary_id',
        'is_active',
        'is_public',
        'established_date',
        'dissolved_date',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'established_date' => 'date',
        'dissolved_date' => 'date',
    ];

    /**
     * Get the chairperson of the committee.
     */
    public function chairperson()
    {
        return $this->belongsTo(\App\Models\User::class, 'chairperson_id');
    }

    /**
     * Get the secretary of the committee.
     */
    public function secretary()
    {
        return $this->belongsTo(\App\Models\User::class, 'secretary_id');
    }

    /**
     * Get the committee members.
     */
    public function members()
    {
        return $this->hasMany(CommitteeMember::class);
    }

    /**
     * Get the committee meetings.
     */
    public function meetings()
    {
        return $this->hasMany(CommitteeMeeting::class);
    }

    /**
     * Get the committee agendas.
     */
    public function agendas()
    {
        return $this->hasMany(CommitteeAgenda::class);
    }

    /**
     * Get the committee resolutions.
     */
    public function resolutions()
    {
        return $this->hasMany(CommitteeResolution::class);
    }
}
