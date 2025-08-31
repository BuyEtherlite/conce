
<?php

class DuplicateCodeFixer
{
    private $duplicatesFound = 0;
    private $duplicatesFixed = 0;
    private $errors = 0;

    public function __construct()
    {
        echo "Starting duplicate code detection and fixing...\n";
        echo "==========================================\n\n";
    }

    /**
     * Run the complete duplicate code fixing process
     */
    public function run()
    {
        $directories = [
            'app/Modules',
            'resources/views',
            'app/Http/Controllers',
            'routes'
        ];

        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                echo "Scanning directory: $directory\n";
                $this->scanDirectory($directory);
            }
        }

        $this->showSummary();
    }

    /**
     * Scan directory for duplicate code patterns
     */
    private function scanDirectory($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        $files = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && (
                $file->getExtension() === 'php' || 
                $file->getExtension() === 'blade' ||
                str_contains($file->getFilename(), '.blade.php')
            )) {
                $files[] = $file->getPathname();
            }
        }

        // Check for duplicate view content
        $this->fixDuplicateViews($files);
        
        // Check for duplicate controller methods
        $this->fixDuplicateControllerMethods($files);
        
        // Check for duplicate route definitions
        $this->fixDuplicateRoutes($files);
        
        // Check for duplicate model definitions
        $this->fixDuplicateModels($files);
    }

    /**
     * Fix duplicate view content
     */
    private function fixDuplicateViews($files)
    {
        $viewFiles = array_filter($files, function($file) {
            return str_contains($file, '.blade.php');
        });

        $viewContents = [];
        
        foreach ($viewFiles as $file) {
            $content = file_get_contents($file);
            if (!$content) continue;

            // Remove whitespace and comments for comparison
            $normalizedContent = $this->normalizeContent($content);
            
            if (isset($viewContents[$normalizedContent])) {
                $this->duplicatesFound++;
                echo "Found duplicate view: $file (duplicate of {$viewContents[$normalizedContent]})\n";
                
                // Check if this is a standard CRUD view that can be templated
                if ($this->isStandardCrudView($content)) {
                    $this->fixStandardCrudView($file, $content);
                }
            } else {
                $viewContents[$normalizedContent] = $file;
            }
        }
    }

    /**
     * Fix duplicate controller methods
     */
    private function fixDuplicateControllerMethods($files)
    {
        $controllerFiles = array_filter($files, function($file) {
            return str_contains($file, 'Controller.php');
        });

        foreach ($controllerFiles as $file) {
            $content = file_get_contents($file);
            if (!$content) continue;

            // Fix duplicate method signatures
            $fixedContent = $this->removeDuplicateMethods($content);
            
            if ($fixedContent !== $content) {
                $this->duplicatesFixed++;
                file_put_contents($file, $fixedContent);
                echo "✓ Fixed duplicate methods in: $file\n";
            }
        }
    }

    /**
     * Fix duplicate route definitions
     */
    private function fixDuplicateRoutes($files)
    {
        $routeFiles = array_filter($files, function($file) {
            return str_contains($file, 'routes/') || str_contains($file, 'Routes/');
        });

        foreach ($routeFiles as $file) {
            $content = file_get_contents($file);
            if (!$content) continue;

            // Remove duplicate route definitions
            $fixedContent = $this->removeDuplicateRoutes($content);
            
            if ($fixedContent !== $content) {
                $this->duplicatesFixed++;
                file_put_contents($file, $fixedContent);
                echo "✓ Fixed duplicate routes in: $file\n";
            }
        }
    }

    /**
     * Fix duplicate model definitions
     */
    private function fixDuplicateModels($files)
    {
        $modelFiles = array_filter($files, function($file) {
            return str_contains($file, '/Models/') && str_ends_with($file, '.php');
        });

        $modelClasses = [];
        
        foreach ($modelFiles as $file) {
            $content = file_get_contents($file);
            if (!$content) continue;

            // Extract class name
            if (preg_match('/class\s+(\w+)/', $content, $matches)) {
                $className = $matches[1];
                
                if (isset($modelClasses[$className])) {
                    $this->duplicatesFound++;
                    echo "Found duplicate model class: $className in $file (duplicate of {$modelClasses[$className]})\n";
                    
                    // Merge models if they're similar
                    $this->mergeDuplicateModels($file, $modelClasses[$className]);
                } else {
                    $modelClasses[$className] = $file;
                }
            }
        }
    }

    /**
     * Remove duplicate methods from controller content
     */
    private function removeDuplicateMethods($content)
    {
        // Find all method definitions
        preg_match_all('/public\s+function\s+(\w+)\s*\([^)]*\)\s*\{[^}]*\}/s', $content, $matches, PREG_SET_ORDER);
        
        $seenMethods = [];
        $methodsToRemove = [];
        
        foreach ($matches as $match) {
            $methodSignature = $this->normalizeMethodSignature($match[0]);
            $methodName = $match[1];
            
            if (isset($seenMethods[$methodSignature])) {
                $methodsToRemove[] = $match[0];
            } else {
                $seenMethods[$methodSignature] = $methodName;
            }
        }
        
        // Remove duplicate methods
        foreach ($methodsToRemove as $method) {
            $content = str_replace($method, '', $content);
        }
        
        return $content;
    }

    /**
     * Remove duplicate route definitions
     */
    private function removeDuplicateRoutes($content)
    {
        // Find all route definitions
        preg_match_all('/Route::\w+\([^;]+;/m', $content, $matches);
        
        $seenRoutes = [];
        $routesToRemove = [];
        
        foreach ($matches[0] as $route) {
            $normalizedRoute = $this->normalizeRoute($route);
            
            if (in_array($normalizedRoute, $seenRoutes)) {
                $routesToRemove[] = $route;
            } else {
                $seenRoutes[] = $normalizedRoute;
            }
        }
        
        // Remove duplicate routes
        foreach ($routesToRemove as $route) {
            $content = str_replace($route, '', $content);
        }
        
        return $content;
    }

    /**
     * Check if view is a standard CRUD view
     */
    private function isStandardCrudView($content)
    {
        $crudPatterns = [
            '@extends(',
            '@section(',
            'create.blade.php',
            'edit.blade.php',
            'index.blade.php',
            'show.blade.php'
        ];
        
        foreach ($crudPatterns as $pattern) {
            if (str_contains($content, $pattern)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Fix standard CRUD view by creating a more generic template
     */
    private function fixStandardCrudView($file, $content)
    {
        // Extract module name from path
        if (preg_match('/\/(\w+)\/Views\//i', $file, $matches)) {
            $moduleName = strtolower($matches[1]);
            
            // Create a more generic view template
            $genericContent = $this->createGenericCrudTemplate($content, $moduleName);
            
            if ($genericContent !== $content) {
                file_put_contents($file, $genericContent);
                $this->duplicatesFixed++;
                echo "✓ Fixed duplicate CRUD view: $file\n";
            }
        }
    }

    /**
     * Create generic CRUD template
     */
    private function createGenericCrudTemplate($content, $moduleName)
    {
        // Replace hardcoded module references with dynamic ones
        $replacements = [
            '/\b' . ucfirst($moduleName) . '\b/' => '{{ ucfirst($module) }}',
            '/\b' . strtolower($moduleName) . '\b/' => '{{ strtolower($module) }}',
            '/\bCreate ' . ucfirst($moduleName) . '\b/' => 'Create {{ ucfirst($module) }}',
            '/\bEdit ' . ucfirst($moduleName) . '\b/' => 'Edit {{ ucfirst($module) }}',
        ];
        
        foreach ($replacements as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        return $content;
    }

    /**
     * Merge duplicate models
     */
    private function mergeDuplicateModels($duplicateFile, $originalFile)
    {
        $duplicateContent = file_get_contents($duplicateFile);
        $originalContent = file_get_contents($originalFile);
        
        // Extract unique properties and methods from duplicate
        $uniqueProperties = $this->extractUniqueModelProperties($duplicateContent, $originalContent);
        
        if (!empty($uniqueProperties)) {
            // Add unique properties to original model
            $mergedContent = $this->addPropertiesToModel($originalContent, $uniqueProperties);
            file_put_contents($originalFile, $mergedContent);
            
            // Remove duplicate file
            unlink($duplicateFile);
            $this->duplicatesFixed++;
            echo "✓ Merged duplicate model: $duplicateFile into $originalFile\n";
        }
    }

    /**
     * Extract unique properties from duplicate model
     */
    private function extractUniqueModelProperties($duplicateContent, $originalContent)
    {
        $uniqueProperties = [];
        
        // Extract fillable arrays
        if (preg_match('/protected\s+\$fillable\s*=\s*\[[^\]]+\];/s', $duplicateContent, $duplicateFillable) &&
            preg_match('/protected\s+\$fillable\s*=\s*\[[^\]]+\];/s', $originalContent, $originalFillable)) {
            
            // Compare fillable arrays and merge unique items
            $duplicateItems = $this->extractArrayItems($duplicateFillable[0]);
            $originalItems = $this->extractArrayItems($originalFillable[0]);
            
            $uniqueItems = array_diff($duplicateItems, $originalItems);
            if (!empty($uniqueItems)) {
                $uniqueProperties['fillable'] = $uniqueItems;
            }
        }
        
        return $uniqueProperties;
    }

    /**
     * Extract array items from fillable definition
     */
    private function extractArrayItems($arrayString)
    {
        preg_match_all("/'([^']+)'/", $arrayString, $matches);
        return $matches[1];
    }

    /**
     * Add properties to model
     */
    private function addPropertiesToModel($content, $properties)
    {
        if (isset($properties['fillable'])) {
            // Find existing fillable and merge
            $content = preg_replace_callback(
                '/protected\s+\$fillable\s*=\s*\[([^\]]+)\];/s',
                function($matches) use ($properties) {
                    $existingItems = $this->extractArrayItems($matches[0]);
                    $mergedItems = array_unique(array_merge($existingItems, $properties['fillable']));
                    $itemsString = "'" . implode("', '", $mergedItems) . "'";
                    return "protected \$fillable = [$itemsString];";
                },
                $content
            );
        }
        
        return $content;
    }

    /**
     * Normalize content for comparison
     */
    private function normalizeContent($content)
    {
        // Remove comments, whitespace, and variable content
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);
        $content = preg_replace('/\/\/.*$/m', '', $content);
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);
        
        return $content;
    }

    /**
     * Normalize method signature for comparison
     */
    private function normalizeMethodSignature($method)
    {
        // Extract just the method signature without variable names
        $method = preg_replace('/\$\w+/', '$var', $method);
        $method = preg_replace('/\s+/', ' ', $method);
        
        return trim($method);
    }

    /**
     * Normalize route for comparison
     */
    private function normalizeRoute($route)
    {
        // Remove variable parts like route names
        $route = preg_replace('/->name\([^)]+\)/', '', $route);
        $route = preg_replace('/\s+/', ' ', $route);
        
        return trim($route);
    }

    /**
     * Show processing summary
     */
    private function showSummary()
    {
        echo "\n==========================================\n";
        echo "Processing Summary:\n";
        echo "==========================================\n";
        echo "Duplicates found: {$this->duplicatesFound}\n";
        echo "Duplicates fixed: {$this->duplicatesFixed}\n";
        echo "Errors encountered: {$this->errors}\n\n";
        
        if ($this->duplicatesFixed > 0) {
            echo "✓ Successfully fixed {$this->duplicatesFixed} duplicate code issues!\n";
        }
        
        if ($this->duplicatesFound > $this->duplicatesFixed) {
            echo "⚠ Some duplicates require manual review.\n";
        }
        
        echo "\nDone! Your Laravel application duplicate code has been cleaned up.\n";
    }
}

// Run the duplicate code fixer
$fixer = new DuplicateCodeFixer();
$fixer->run();
