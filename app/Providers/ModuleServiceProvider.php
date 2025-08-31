<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Available modules in the system
     */
    protected $modules = [
        'Finance',
        'Housing', 
        'Water',
        'Committee',
        'Administration',
        'Property',
        'Utilities',
        'Health',
        'Survey',
        'Licensing',
        'Parking',
        'Markets',
        'Inventory',
        'HR',
        'Emergency',
        'Engineering',
        'Events',
        'Cemeteries',
        'Planning',
        'PropertyTax',
        'PublicServices',
        'Billing'
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Register module services
        foreach ($this->modules as $module) {
            $this->registerModuleServices($module);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load module routes
        $this->loadModuleRoutes();
        
        // Load module views
        $this->loadModuleViews();
        
        // Load module migrations
        $this->loadModuleMigrations();
    }

    /**
     * Register services for a specific module
     */
    protected function registerModuleServices(string $module): void
    {
        $serviceClass = "App\\Modules\\{$module}\\Services\\{$module}Service";
        
        if (class_exists($serviceClass)) {
            $this->app->singleton($serviceClass);
        }
    }

    /**
     * Load routes for all modules
     */
    protected function loadModuleRoutes(): void
    {
        foreach ($this->modules as $module) {
            $routePath = app_path("Modules/{$module}/Routes/web.php");
            
            if (File::exists($routePath)) {
                Route::middleware(['web', 'auth'])
                    ->prefix(strtolower($module))
                    ->name(strtolower($module) . '.')
                    ->group($routePath);
            }

            $apiRoutePath = app_path("Modules/{$module}/Routes/api.php");
            
            if (File::exists($apiRoutePath)) {
                Route::middleware(['api'])
                    ->prefix('api/' . strtolower($module))
                    ->name('api.' . strtolower($module) . '.')
                    ->group($apiRoutePath);
            }
        }
    }

    /**
     * Load views for all modules
     */
    protected function loadModuleViews(): void
    {
        foreach ($this->modules as $module) {
            $viewPath = app_path("Modules/{$module}/Views");
            
            if (File::isDirectory($viewPath)) {
                $this->loadViewsFrom($viewPath, strtolower($module));
            }
        }
    }

    /**
     * Load migrations for all modules
     */
    protected function loadModuleMigrations(): void
    {
        foreach ($this->modules as $module) {
            $migrationPath = app_path("Modules/{$module}/Migrations");
            
            if (File::isDirectory($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }
    }

    /**
     * Get list of available modules
     */
    public static function getModules(): array
    {
        return (new static(app()))->modules;
    }

    /**
     * Check if a module is enabled
     */
    public static function isModuleEnabled(string $module): bool
    {
        return in_array($module, static::getModules());
    }
}
