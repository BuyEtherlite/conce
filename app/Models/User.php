<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'department_id',
        'office_id',
        'council_id',
        'employee_id',
        'phone',
        'position',
        'permissions',
        'hire_date',
        'salary',
        'employment_status',
        'profile_photo',
    ];

    // Original role constants that are now duplicated or modified
    // const ROLE_SUPER_ADMIN = 'super_admin';
    // const ROLE_ADMIN = 'admin';
    // const ROLE_USER = 'user';
    // const ROLE_POS_OPERATOR = 'pos_operator';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
        'permissions' => 'array',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Check if user can access a specific module
     */
    public function canAccessModule($module)
    {
        // Admins can access everything including core module management
        if ($this->role === 'admin') {
            return true;
        }

        // Check if user's department has access to this module
        if ($this->department && $this->department->modules_access) {
            $allowedModules = is_array($this->department->modules_access)
                ? $this->department->modules_access
                : json_decode($this->department->modules_access, true) ?? [];

            return in_array($module, $allowedModules);
        }

        return false;
    }

    /**
     * Check if user is super admin
     */
    // public function isSuperAdmin()
    // {
    //     return $this->role === 'super_admin' || $this->is_super_admin === true;
    // }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    /**
     * Check if user is admin or higher
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is manager or higher
     */
    public function isManager()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    /**
     * Get formatted role name
     */
    public function getRoleNameAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->role));
    }
}