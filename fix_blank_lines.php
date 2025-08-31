<?php

/**
 * Script to remove blank lines from line 1 of all PHP files
 * This will scan all PHP files in the project and fix any that have blank lines at the beginning
 */

class BlankLinesFixer
{
    private $processedFiles = 0;
    private $fixedFiles = 0;
    private $errors = [];

    public function __construct()
    {
        echo "Starting blank lines fix for PHP files...\n";
        echo "==========================================\n\n";
    }

    /**
     * Recursively scan directory for PHP files
     */
    public function scanDirectory($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }
    }

    /**
     * Process individual PHP file
     */
    private function processFile($filePath)
    {
        $this->processedFiles++;
        
        try {
            // Read file contents
            $content = file_get_contents($filePath);
            
            if ($content === false) {
                $this->errors[] = "Could not read file: $filePath";
                return;
            }

            // Check if file starts with blank lines
            if ($this->hasBlankLinesAtStart($content)) {
                $fixedContent = $this->removeBlankLinesFromStart($content);
                
                // Write fixed content back to file
                if (file_put_contents($filePath, $fixedContent) !== false) {
                    $this->fixedFiles++;
                    echo "✓ Fixed: $filePath\n";
                } else {
                    $this->errors[] = "Could not write to file: $filePath";
                }
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Error processing $filePath: " . $e->getMessage();
        }
    }

    /**
     * Check if content has blank lines at the start
     */
    private function hasBlankLinesAtStart($content)
    {
        // Check if content starts with whitespace before <?php
        return preg_match('/^\s+<\?php/', $content) || 
               preg_match('/^(\r?\n|\r)+/', $content);
    }

    /**
     * Remove blank lines from start of content
     */
    private function removeBlankLinesFromStart($content)
    {
        // Remove any leading whitespace and newlines
        $content = ltrim($content, " \t\n\r\0\x0B");
        
        // Ensure it starts with <?php if it's a PHP file
        if (!str_starts_with($content, '<?php')) {
            // If no PHP opening tag found, look for it after whitespace
            if (preg_match('/^(\s*)<\?php(.*)$/s', $content, $matches)) {
                $content = '<?php' . $matches[2];
            }
        }
        
        return $content;
    }

    /**
     * Get list of directories to scan
     */
    private function getDirectoriesToScan()
    {
        return [
            'app',
            'config',
            'database',
            'routes',
            'tests',
            'resources/views' // For Blade files that might have PHP
        ];
    }

    /**
     * Run the complete fix process
     */
    public function run()
    {
        $directories = $this->getDirectoriesToScan();
        
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                echo "Scanning directory: $directory\n";
                $this->scanDirectory($directory);
            } else {
                echo "Directory not found: $directory\n";
            }
        }

        // Also check root PHP files
        $rootFiles = glob('*.php');
        foreach ($rootFiles as $file) {
            $this->processFile($file);
        }

        $this->showSummary();
    }

    /**
     * Show processing summary
     */
    private function showSummary()
    {
        echo "\n==========================================\n";
        echo "Processing Summary:\n";
        echo "==========================================\n";
        echo "Total files processed: {$this->processedFiles}\n";
        echo "Files fixed: {$this->fixedFiles}\n";
        echo "Errors encountered: " . count($this->errors) . "\n";

        if (!empty($this->errors)) {
            echo "\nErrors:\n";
            foreach ($this->errors as $error) {
                echo "✗ $error\n";
            }
        }

        if ($this->fixedFiles > 0) {
            echo "\n✓ Successfully fixed {$this->fixedFiles} files!\n";
        } else {
            echo "\n✓ No files needed fixing - all files are clean!\n";
        }
    }
}

// Run the script
$fixer = new BlankLinesFixer();
$fixer->run();

echo "\nDone! You can now run your Laravel application.\n";
