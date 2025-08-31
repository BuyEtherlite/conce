<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Administration\CoreModulesController;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        // For now, allow all modules for all authenticated users
        if (auth()->check()) {
            return $next($request);
        }

        // If not authenticated, redirect to login
        return redirect()->route('login');
    }

    /**
     * Check if a module is enabled
     */
    private function isModuleEnabled($moduleKey)
    {
        try {
            // Check if core_modules table exists
            if (!\DB::getSchemaBuilder()->hasTable('core_modules')) {
                return true; // If table doesn't exist, assume all modules are enabled
            }

            $module = \App\Models\CoreModule::where('module_key', $moduleKey)->first();
            
            if (!$module) {
                // If module doesn't exist in database, assume it's enabled
                return true;
            }

            return $module->is_active;
        } catch (\Exception $e) {
            \Log::warning("Could not check module status for {$moduleKey}: " . $e->getMessage());
            return true; // Default to enabled if we can't check
        }
    }
}
