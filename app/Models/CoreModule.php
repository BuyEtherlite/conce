
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreModule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'module_key',
        'display_name',
        'description',
        'icon',
        'is_active',
        'is_core',
        'permissions',
        'version',
        'sort_order',
        'configuration',
        'dependencies'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_core' => 'boolean',
        'permissions' => 'array',
        'configuration' => 'array',
        'dependencies' => 'array'
    ];

    protected $attributes = [
        'is_active' => true,
        'is_core' => false,
        'version' => '1.0.0',
        'sort_order' => 0
    ];

    /**
     * Get the features for this module.
     */
    public function features()
    {
        return $this->hasMany(ModuleFeature::class, 'core_module_id')->orderBy('sort_order');
    }

    /**
     * Get enabled features for this module.
     */
    public function enabledFeatures()
    {
        return $this->hasMany(ModuleFeature::class, 'core_module_id')->where('is_enabled', true);
    }

    /**
     * Get users who have access to this module
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_module_access', 'module_id', 'user_id');
    }

    /**
     * Scope to get only active modules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get core modules
     */
    public function scopeCore($query)
    {
        return $query->where('is_core', true);
    }

    /**
     * Scope to get modules by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the icon name for this module
     */
    public function getIconAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $iconMap = [
            'administration' => 'users-cog',
            'finance' => 'dollar-sign',
            'housing' => 'home',
            'facilities' => 'swimming-pool',
            'planning' => 'building',
            'health' => 'heartbeat',
            'licensing' => 'certificate',
            'utilities' => 'bolt',
            'water' => 'tint',
            'engineering' => 'tools',
            'hr' => 'user-tie',
            'committee' => 'users',
            'parking' => 'car',
            'markets' => 'store',
            'inventory' => 'boxes',
            'survey' => 'map',
            'property' => 'building',
            'cemeteries' => 'cross',
            'events' => 'calendar',
        ];

        $moduleKey = $this->module_key ?: $this->name;
        return $iconMap[$moduleKey] ?? 'cube';
    }

    /**
     * Get icon for module (method version)
     */
    public function getIcon()
    {
        return $this->getIconAttribute($this->attributes['icon'] ?? null);
    }

    /**
     * Check if module is enabled
     */
    public function isEnabled()
    {
        return $this->is_active ?? true;
    }

    /**
     * Enable the module
     */
    public function enable()
    {
        $this->update(['is_active' => true]);
        return $this;
    }

    /**
     * Disable the module
     */
    public function disable()
    {
        $this->update(['is_active' => false]);
        return $this;
    }

    /**
     * Get total features count
     */
    public function getTotalFeaturesCount()
    {
        return $this->features()->count();
    }

    /**
     * Get enabled features count
     */
    public function getEnabledFeaturesCount()
    {
        return $this->features()->where('is_enabled', true)->count();
    }

    /**
     * Check if module is core
     */
    public function isCore()
    {
        return $this->is_core;
    }

    /**
     * Check if specific feature is enabled
     */
    public function isFeatureEnabled($featureKey)
    {
        if (!$this->is_active) {
            return false;
        }

        $feature = $this->features()->where('feature_key', $featureKey)->first();
        return $feature ? $feature->is_enabled : false;
    }

    /**
     * Check if module has specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->is_active) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Get display name with fallback
     */
    public function getDisplayName()
    {
        return $this->display_name ?: ucwords(str_replace(['_', '-'], ' ', $this->name));
    }

    /**
     * Get module configuration
     */
    public function getConfiguration()
    {
        return $this->configuration ?? [];
    }

    /**
     * Set module configuration
     */
    public function setConfiguration(array $config)
    {
        $this->update(['configuration' => $config]);
        return $this;
    }

    /**
     * Check if all dependencies are met
     */
    public function dependenciesMet()
    {
        $dependencies = $this->dependencies ?? [];
        
        foreach ($dependencies as $dependency) {
            $dependentModule = static::where('name', $dependency)->first();
            if (!$dependentModule || !$dependentModule->is_active) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get module statistics
     */
    public function getStatistics()
    {
        return [
            'total_features' => $this->getTotalFeaturesCount(),
            'enabled_features' => $this->getEnabledFeaturesCount(),
            'users_with_access' => $this->users()->count(),
            'last_updated' => $this->updated_at,
        ];
    }

    /**
     * Search modules by name or description
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('display_name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Get module route prefix
     */
    public function getRoutePrefix()
    {
        return strtolower(str_replace(['_', ' '], '-', $this->name));
    }

    /**
     * Generate module menu items
     */
    public function getMenuItems()
    {
        $routePrefix = $this->getRoutePrefix();
        
        $menuItems = [
            'dashboard' => [
                'title' => 'Dashboard',
                'route' => "{$routePrefix}.index",
                'icon' => 'tachometer-alt',
            ]
        ];

        // Add feature-specific menu items
        foreach ($this->enabledFeatures as $feature) {
            $menuItems[$feature->feature_key] = [
                'title' => $feature->feature_name,
                'route' => "{$routePrefix}.{$feature->feature_key}",
                'icon' => $feature->icon ?? 'circle',
            ];
        }

        return $menuItems;
    }
}
