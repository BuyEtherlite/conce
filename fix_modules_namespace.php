<?php

/**
 * Script to fix all module namespaces to follow PSR-4 standard
 */

$modulesPath = __DIR__ . '/app/Modules';
$modules = ['Finance', 'Housing', 'Water', 'Committee', 'Health', 'Licensing'];

function fixNamespace($filePath, $expectedNamespace) {
    if (!file_exists($filePath)) {
        return;
    }
    
    $content = file_get_contents($filePath);
    
    // Fix namespace declaration
    $content = preg_replace(
        '/^namespace\s+App\\\\Models\\\\([^;]+);/m',
        'namespace ' . $expectedNamespace . ';',
        $content
    );
    
    $content = preg_replace(
        '/^namespace\s+App\\\\Http\\\\Controllers\\\\([^;]+);/m',
        'namespace ' . $expectedNamespace . ';',
        $content
    );
    
    // Fix use statements
    $content = preg_replace(
        '/use\s+App\\\\Models\\\\([^;]+);/m',
        'use App\\Modules\\$1;',
        $content
    );
    
    $content = preg_replace(
        '/use\s+App\\\\Http\\\\Controllers\\\\([^;]+);/m',
        'use App\\Modules\\$1;',
        $content
    );
    
    file_put_contents($filePath, $content);
    echo "Fixed: $filePath\n";
}

foreach ($modules as $module) {
    // Fix Models
    $modelsPath = $modulesPath . '/' . $module . '/Models';
    if (is_dir($modelsPath)) {
        $modelFiles = glob($modelsPath . '/*.php');
        foreach ($modelFiles as $file) {
            $expectedNamespace = 'App\\Modules\\' . $module . '\\Models';
            fixNamespace($file, $expectedNamespace);
        }
    }
    
    // Fix Controllers
    $controllersPath = $modulesPath . '/' . $module . '/Controllers';
    if (is_dir($controllersPath)) {
        $controllerFiles = glob($controllersPath . '/*.php');
        foreach ($controllerFiles as $file) {
            $expectedNamespace = 'App\\Modules\\' . $module . '\\Controllers';
            fixNamespace($file, $expectedNamespace);
        }
    }
}

echo "Namespace fixing completed!\n";
