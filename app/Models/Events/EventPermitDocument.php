<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EventPermitDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_permit_id',
        'document_type',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'is_required',
        'is_verified',
        'verified_at',
        'verified_by',
        'uploaded_by',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function eventPermit(): BelongsTo
    {
        return $this->belongsTo(EventPermit::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if ($this->file_size < 1024) {
            return $this->file_size . ' B';
        } elseif ($this->file_size < 1024 * 1024) {
            return round($this->file_size / 1024, 1) . ' KB';
        } else {
            return round($this->file_size / (1024 * 1024), 1) . ' MB';
        }
    }
}
