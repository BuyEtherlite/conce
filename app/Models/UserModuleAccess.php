<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModuleAccess extends Model
{
    protected $table = 'user_module_access';
    
    protected $fillable = [
        'user_id',
        'module_name',
        'feature_key',
        'has_access'
    ];

    protected $casts = [
        'has_access' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForModule($query, $moduleName)
    {
        return $query->where('module_name', $moduleName);
    }
}
