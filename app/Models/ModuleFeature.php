<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleFeature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'core_module_id',
        'module_name',
        'feature_name',
        'feature_key',
        'description',
        'icon',
        'is_enabled',
        'permissions',
        'sort_order',
        'configuration',
        'route_name',
        'controller_action'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'permissions' => 'array',
        'configuration' => 'array'
    ];

    protected $attributes = [
        'is_enabled' => true,
        'sort_order' => 0
    ];

    /**
     * Get the module this feature belongs to
     */
    public function module()
    {
        return $this->belongsTo(CoreModule::class, 'core_module_id');
    }

    /**
     * Alias for module relationship
     */
    public function coreModule()
    {
        return $this->belongsTo(CoreModule::class, 'core_module_id');
    }

    /**
     * Scope to get enabled features
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope to get features for specific module
     */
    public function scopeForModule($query, $moduleName)
    {
        return $query->where('module_name', $moduleName)
                    ->orWhereHas('coreModule', function($q) use ($moduleName) {
                        $q->where('name', $moduleName);
                    });
    }

    /**
     * Scope to order features
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('feature_name', 'asc');
    }

    /**
     * Check if feature is enabled (considering parent module)
     */
    public function isEnabled()
    {
        return $this->is_enabled && ($this->coreModule ? $this->coreModule->is_active : true);
    }

    /**
     * Check if feature has specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Enable this feature
     */
    public function enable()
    {
        $this->update(['is_enabled' => true]);
        return $this;
    }

    /**
     * Disable this feature
     */
    public function disable()
    {
        $this->update(['is_enabled' => false]);
        return $this;
    }

    /**
     * Get feature configuration
     */
    public function getConfiguration()
    {
        return $this->configuration ?? [];
    }

    /**
     * Set feature configuration
     */
    public function setConfiguration(array $config)
    {
        $this->update(['configuration' => $config]);
        return $this;
    }

    /**
     * Get feature route name
     */
    public function getRouteName()
    {
        if ($this->route_name) {
            return $this->route_name;
        }

        $moduleRoute = $this->coreModule ? $this->coreModule->getRoutePrefix() : strtolower($this->module_name);
        return "{$moduleRoute}.{$this->feature_key}";
    }

    /**
     * Get feature URL
     */
    public function getUrl()
    {
        try {
            return route($this->getRouteName());
        } catch (\Exception $e) {
            return '#';
        }
    }

    /**
     * Get feature icon
     */
    public function getFeatureIcon()
    {
        if ($this->icon) {
            return $this->icon;
        }

        // Default icons based on feature key
        $iconMap = [
            'dashboard' => 'tachometer-alt',
            'users' => 'users',
            'settings' => 'cogs',
            'reports' => 'chart-line',
            'billing' => 'file-invoice-dollar',
            'payments' => 'credit-card',
            'inventory' => 'boxes',
            'documents' => 'file-alt',
        ];

        return $iconMap[$this->feature_key] ?? 'circle';
    }

    // Backward compatibility attributes
    public function getEnabledAttribute()
    {
        return $this->is_enabled;
    }

    public function getNameAttribute()
    {
        return $this->feature_name;
    }

    public function getDisplayNameAttribute()
    {
        return $this->feature_name;
    }

    /**
     * Search features
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('feature_name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('feature_key', 'like', "%{$term}%");
        });
    }

    /**
     * Get usage statistics for this feature
     */
    public function getUsageStats()
    {
        // This would need to be implemented based on actual usage tracking
        return [
            'total_uses' => 0,
            'unique_users' => 0,
            'last_used' => null,
        ];
    }
}
