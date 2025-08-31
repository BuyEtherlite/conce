<?php

namespace App\Modules\HR\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FaceEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'encoding_file_path',
        'sample_image_path',
        'quality_score',
        'face_landmarks',
        'status',
        'enrolled_by',
        'expires_at'
    ];

    protected $casts = [
        'quality_score' => 'decimal:2',
        'face_landmarks' => 'array',
        'expires_at' => 'datetime'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function enrolledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    public function getFaceEncodingData(): ?array
    {
        if (!$this->encoding_file_path || !Storage::exists($this->encoding_file_path)) {
            return null;
        }

        $data = Storage::get($this->encoding_file_path);
        return json_decode($data, true);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($enrollment) {
            // Delete associated files when enrollment is deleted
            if ($enrollment->encoding_file_path && Storage::exists($enrollment->encoding_file_path)) {
                Storage::delete($enrollment->encoding_file_path);
            }
            if ($enrollment->sample_image_path && Storage::exists($enrollment->sample_image_path)) {
                Storage::delete($enrollment->sample_image_path);
            }
        });
    }
}
