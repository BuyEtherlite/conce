<?php

/**
 * Script to find all Blade views that don't extend layouts.admin
 * These views won't have the sidebar navigation
 */

function scanViewsDirectory($directory) {
    $viewsWithoutSidenav = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $relativePath = str_replace($directory . '/', '', $file->getPathname());
            
            // Skip non-blade files
            if (!str_ends_with($relativePath, '.blade.php')) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            
            // Check if the file extends layouts.admin
            $extendsAdmin = preg_match('/@extends\s*\(\s*[\'"]layouts\.admin[\'"]\s*\)/', $content);
            
            // Also check for other layout extensions
            preg_match('/@extends\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/', $content, $matches);
            $extendsLayout = isset($matches[1]) ? $matches[1] : 'none';
            
            if (!$extendsAdmin) {
                $viewsWithoutSidenav[] = [
                    'file' => $relativePath,
                    'extends' => $extendsLayout,
                    'has_extends' => !empty($matches[1])
                ];
            }
        }
    }

    return $viewsWithoutSidenav;
}

function displayResults($views) {
    echo "=== BLADE VIEWS WITHOUT SIDEBAR NAVIGATION ===\n\n";
    echo "Found " . count($views) . " views that don't extend 'layouts.admin'\n\n";
    
    // Group by layout type
    $grouped = [];
    foreach ($views as $view) {
        $layout = $view['extends'];
        if (!isset($grouped[$layout])) {
            $grouped[$layout] = [];
        }
        $grouped[$layout][] = $view['file'];
    }
    
    foreach ($grouped as $layout => $files) {
        echo "--- Extending '$layout' (" . count($files) . " files) ---\n";
        foreach ($files as $file) {
            echo "  • $file\n";
        }
        echo "\n";
    }
    
    // Show recommendations
    echo "=== RECOMMENDATIONS ===\n";
    echo "Consider updating these views to extend 'layouts.admin' if they need sidebar navigation:\n\n";
    
    foreach ($views as $view) {
        if ($view['extends'] === 'layouts.app' || $view['extends'] === 'none') {
            echo "  • {$view['file']} (currently extends: {$view['extends']})\n";
        }
    }
}

// Main execution
$viewsDirectory = __DIR__ . '/resources/views';

if (!is_dir($viewsDirectory)) {
    echo "Error: Views directory not found at $viewsDirectory\n";
    exit(1);
}

echo "Scanning views directory: $viewsDirectory\n";
echo "Looking for views that don't extend 'layouts.admin'...\n\n";

$viewsWithoutSidenav = scanViewsDirectory($viewsDirectory);
displayResults($viewsWithoutSidenav);

// Generate a summary report
echo "\n=== SUMMARY REPORT ===\n";
echo "Total views scanned: " . count(glob($viewsDirectory . '/**/*.blade.php', GLOB_BRACE)) . "\n";
echo "Views without admin layout: " . count($viewsWithoutSidenav) . "\n";

// Count by category
$categories = [
    'auth' => 0,
    'install' => 0,
    'layouts' => 0,
    'other' => 0
];

foreach ($viewsWithoutSidenav as $view) {
    if (str_starts_with($view['file'], 'auth/')) {
        $categories['auth']++;
    } elseif (str_starts_with($view['file'], 'install/')) {
        $categories['install']++;
    } elseif (str_starts_with($view['file'], 'layouts/')) {
        $categories['layouts']++;
    } else {
        $categories['other']++;
    }
}

echo "\nBreakdown by category:\n";
foreach ($categories as $category => $count) {
    if ($count > 0) {
        echo "  • $category: $count views\n";
    }
}

echo "\nDone!\n";
