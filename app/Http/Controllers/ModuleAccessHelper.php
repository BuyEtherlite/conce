<?php

namespace App\Http\Controllers;

trait ModuleAccessHelper
{
    protected function checkModuleAccess($module)
    {
        // All modules are now always accessible
        return true;
    }

    protected function requireModuleAccess($module)
    {
        // No restrictions - all modules are accessible
        return true;
    }
}
