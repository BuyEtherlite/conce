<?php

/**
 * Fix Target Class Errors Script
 * This script identifies and fixes common target class resolution issues
 */

class TargetClassErrorFixer
{
    private $errors = [];
    private $fixes = [];

    public function run()
    {
        echo "Starting Target Class Error Fixes...\n\n";
        
        $this->checkControllerNamespaces();
        $this->checkModelReferences();
        $this->checkServiceProviderBindings();
        $this->fixComposerAutoload();
        $this->validateRouteReferences();
        
        $this->displayResults();
    }

    private function checkControllerNamespaces()
    {
        echo "Checking controller namespaces...\n";
        
        $controllerDirs = [
            'app/Http/Controllers',
            'app/Modules/*/Controllers'
        ];
        
        foreach ($controllerDirs as $dir) {
            if ($dir === 'app/Modules/*/Controllers') {
                $moduleDirs = glob('app/Modules/*/Controllers', GLOB_ONLYDIR);
                foreach ($moduleDirs as $moduleDir) {
                    $this->checkControllersInDirectory($moduleDir);
                }
            } else {
                $this->checkControllersInDirectory($dir);
            }
        }
    }

    private function checkControllersInDirectory($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $expectedNamespace = $this->getExpectedNamespace($file);
            
            if (!preg_match('/namespace\s+(.+?);/', $content, $matches)) {
                $this->errors[] = "Missing namespace in: $file";
                $this->fixNamespace($file, $expectedNamespace);
            } else {
                $actualNamespace = trim($matches[1]);
                if ($actualNamespace !== $expectedNamespace) {
                    $this->errors[] = "Incorrect namespace in: $file (Expected: $expectedNamespace, Found: $actualNamespace)";
                    $this->fixNamespace($file, $expectedNamespace);
                }
            }
        }
    }

    private function getExpectedNamespace($filePath)
    {
        $relativePath = str_replace(getcwd() . '/', '', $filePath);
        $pathParts = explode('/', dirname($relativePath));
        
        // Remove filename and convert to namespace
        $namespaceParts = [];
        $inApp = false;
        
        foreach ($pathParts as $part) {
            if ($part === 'app') {
                $inApp = true;
                $namespaceParts[] = 'App';
                continue;
            }
            
            if ($inApp) {
                $namespaceParts[] = ucfirst($part);
            }
        }
        
        return implode('\\', $namespaceParts);
    }

    private function fixNamespace($file, $expectedNamespace)
    {
        $content = file_get_contents($file);
        
        if (preg_match('/namespace\s+(.+?);/', $content)) {
            $content = preg_replace('/namespace\s+(.+?);/', "namespace $expectedNamespace;", $content);
        } else {
            // Insert namespace after opening PHP tag
            $content = preg_replace('/(<\?php\s*)/', "$1\n\nnamespace $expectedNamespace;\n", $content);
        }
        
        file_put_contents($file, $content);
        $this->fixes[] = "Fixed namespace in: $file";
    }

    private function checkModelReferences()
    {
        echo "Checking model references...\n";
        
        $modelFiles = glob('app/Models/*.php');
        foreach ($modelFiles as $file) {
            $content = file_get_contents($file);
            if (!preg_match('/namespace\s+App\\\\Models;/', $content)) {
                $this->fixModelNamespace($file);
            }
        }
        
        // Check module models
        $moduleModelDirs = glob('app/Modules/*/Models', GLOB_ONLYDIR);
        foreach ($moduleModelDirs as $dir) {
            $modelFiles = glob($dir . '/*.php');
            foreach ($modelFiles as $file) {
                $content = file_get_contents($file);
                $expectedNamespace = $this->getExpectedNamespace($file);
                if (!preg_match("/namespace\s+" . preg_quote($expectedNamespace, '/') . ";/", $content)) {
                    $this->fixNamespace($file, $expectedNamespace);
                }
            }
        }
    }

    private function fixModelNamespace($file)
    {
        $content = file_get_contents($file);
        $expectedNamespace = $this->getExpectedNamespace($file);
        
        if (preg_match('/namespace\s+(.+?);/', $content)) {
            $content = preg_replace('/namespace\s+(.+?);/', "namespace $expectedNamespace;", $content);
        } else {
            $content = preg_replace('/(<\?php\s*)/', "$1\n\nnamespace $expectedNamespace;\n", $content);
        }
        
        file_put_contents($file, $content);
        $this->fixes[] = "Fixed model namespace in: $file";
    }

    private function checkServiceProviderBindings()
    {
        echo "Checking service provider bindings...\n";
        
        $providerFiles = [
            'app/Providers/AppServiceProvider.php',
            'app/Providers/ModuleServiceProvider.php'
        ];
        
        foreach ($providerFiles as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                // Check for common binding issues
                $this->validateServiceBindings($file, $content);
            }
        }
    }

    private function validateServiceBindings($file, $content)
    {
        // Look for bind() calls with potentially non-existent classes
        if (preg_match_all('/\$this->app->bind\([\'"]([^\'"]+)[\'"]/', $content, $matches)) {
            foreach ($matches[1] as $className) {
                if (!class_exists($className)) {
                    $this->errors[] = "Service binding references non-existent class: $className in $file";
                }
            }
        }
    }

    private function fixComposerAutoload()
    {
        echo "Regenerating composer autoload...\n";
        
        exec('composer dump-autoload --optimize 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            $this->fixes[] = "Regenerated composer autoload successfully";
        } else {
            $this->errors[] = "Failed to regenerate composer autoload: " . implode("\n", $output);
        }
    }

    private function validateRouteReferences()
    {
        echo "Validating route references...\n";
        
        $routeFiles = [
            'routes/web.php',
            'routes/api.php'
        ];
        
        foreach ($routeFiles as $file) {
            if (file_exists($file)) {
                $this->checkRouteFile($file);
            }
        }
        
        // Check module routes
        $moduleRoutes = glob('app/Modules/*/Routes/*.php');
        foreach ($moduleRoutes as $file) {
            $this->checkRouteFile($file);
        }
    }

    private function checkRouteFile($file)
    {
        $content = file_get_contents($file);
        
        // Look for controller references in routes
        if (preg_match_all('/([A-Za-z\\\\]+Controller)::class/', $content, $matches)) {
            foreach ($matches[1] as $controllerClass) {
                if (!class_exists($controllerClass)) {
                    $this->errors[] = "Route references non-existent controller: $controllerClass in $file";
                }
            }
        }
    }

    private function displayResults()
    {
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "TARGET CLASS ERROR FIX RESULTS\n";
        echo str_repeat("=", 50) . "\n";
        
        if (!empty($this->fixes)) {
            echo "\nFIXES APPLIED:\n";
            foreach ($this->fixes as $fix) {
                echo "✓ $fix\n";
            }
        }
        
        if (!empty($this->errors)) {
            echo "\nERRORS FOUND:\n";
            foreach ($this->errors as $error) {
                echo "✗ $error\n";
            }
        }
        
        if (empty($this->errors) && !empty($this->fixes)) {
            echo "\n✅ All target class errors have been fixed!\n";
        } elseif (empty($this->errors) && empty($this->fixes)) {
            echo "\n✅ No target class errors found!\n";
        } else {
            echo "\n⚠️  Some issues may require manual attention.\n";
        }
        
        echo "\nRecommended next steps:\n";
        echo "1. Run: composer dump-autoload --optimize\n";
        echo "2. Clear Laravel caches: php artisan cache:clear\n";
        echo "3. Clear config cache: php artisan config:clear\n";
        echo "4. Clear route cache: php artisan route:clear\n";
    }
}

// Run the fixer
$fixer = new TargetClassErrorFixer();
$fixer->run();
