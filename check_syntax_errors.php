<?php

class SyntaxErrorChecker
{
    private $directories = [
        'app',
        'config',
        'database',
        'routes',
        'resources/views'
    ];
    
    private $fixedFiles = [];
    private $errorFiles = [];

    public function run()
    {
        echo "Starting syntax error check and fix...\n\n";
        
        foreach ($this->directories as $directory) {
            if (is_dir($directory)) {
                echo "Scanning directory: $directory\n";
                $this->scanDirectory($directory);
            }
        }
        
        $this->showSummary();
    }

    private function scanDirectory($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->checkFile($file->getPathname());
            }
        }
    }

    private function checkFile($filePath)
    {
        $content = file_get_contents($filePath);
        $originalContent = $content;
        $hasChanges = false;

        // Check for syntax errors first
        if (!$this->hasSyntaxError($filePath)) {
            return;
        }

        echo "Checking file: $filePath\n";

        // Common fixes for "unexpected token $1" and similar errors
        $fixes = [
            // Fix malformed SQL queries with unescaped quotes
            '/\'([^\']*)\$1([^\']*)\'/m' => function($matches) {
                return "'" . str_replace('$1', '?', $matches[0]) . "'";
            },
            
            // Fix unescaped dollar signs in strings
            '/\"([^\"]*)\$([0-9]+)([^\"]*)\"/m' => function($matches) {
                return '"' . str_replace('$' . $matches[2], '\\$' . $matches[2], $matches[0]) . '"';
            },
            
            // Fix malformed array syntax
            '/\[\$([0-9]+)\]/' => '[$1]',
            
            // Fix malformed variable references
            '/\$\$([a-zA-Z_][a-zA-Z0-9_]*)/' => '$$1',
            
            // Fix SQL placeholders that got mangled
            '/\'\$([0-9]+)\'/m' => '?',
            '/"\\$([0-9]+)"/m' => '?',
            
            // Fix common string interpolation issues
            '/\$\{([^}]+)\}/' => '${$1}',
            
            // Fix malformed heredoc/nowdoc
            '/<<<([A-Z_]+)\$([0-9]+)/' => '<<<$1',
            
            // Fix PostgreSQL parameter placeholders that got corrupted
            '/where\s+["`]?[a-zA-Z_][a-zA-Z0-9_]*["`]?\s*=\s*\$([0-9]+)/i' => function($matches) {
                return str_replace('$' . $matches[1], '?', $matches[0]);
            },
            
            // Fix SQL parameter binding issues
            '/\bpgsql\b.*\$([0-9]+)/' => function($matches) {
                return str_replace('$' . $matches[1], '?', $matches[0]);
            }
        ];

        foreach ($fixes as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $content = preg_replace_callback($pattern, $replacement, $content);
            } else {
                $content = preg_replace($pattern, $replacement, $content);
            }
        }

        // Additional specific fixes for common Laravel/PostgreSQL issues
        $content = $this->fixPostgreSQLPlaceholders($content);
        $content = $this->fixStringInterpolation($content);
        $content = $this->fixMalformedQueries($content);

        if ($content !== $originalContent) {
            $hasChanges = true;
            file_put_contents($filePath, $content);
            $this->fixedFiles[] = $filePath;
            echo "  ✓ Fixed syntax issues in: $filePath\n";
        }

        // Check if syntax is now valid
        if ($this->hasSyntaxError($filePath)) {
            $this->errorFiles[] = $filePath;
            echo "  ✗ Still has syntax errors: $filePath\n";
        } elseif ($hasChanges) {
            echo "  ✓ Syntax now valid: $filePath\n";
        }
    }

    private function fixPostgreSQLPlaceholders($content)
    {
        // Fix PostgreSQL placeholders in SQL strings
        $patterns = [
            // Common PostgreSQL parameter patterns that get corrupted
            '/\bSQL:\s*([^)]+)\$([0-9]+)([^)]*)\)/' => function($matches) {
                return 'SQL: ' . str_replace('$' . $matches[2], '?', $matches[1] . $matches[3]) . ')';
            },
            
            // Fix parameter placeholders in query strings
            '/select\s+.*\bwhere\s+.*\$([0-9]+).*limit\s+1/i' => function($matches) {
                return preg_replace('/\$[0-9]+/', '?', $matches[0]);
            }
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $content = preg_replace_callback($pattern, $replacement, $content);
            } else {
                $content = preg_replace($pattern, $replacement, $content);
            }
        }

        return $content;
    }

    private function fixStringInterpolation($content)
    {
        // Fix common string interpolation issues
        $lines = explode("\n", $content);
        $fixed = false;

        foreach ($lines as $index => $line) {
            $originalLine = $line;
            
            // Fix variables that got corrupted with $1, $2, etc.
            if (preg_match('/\$[0-9]+/', $line) && !preg_match('/\bSQL:/', $line)) {
                // If it's not in a SQL context, it might be a corrupted variable
                $line = preg_replace('/\$([0-9]+)/', '$var$1', $line);
            }
            
            // Fix malformed array access
            $line = preg_replace('/\[\$([0-9]+)\]/', '[$1]', $line);
            
            if ($line !== $originalLine) {
                $lines[$index] = $line;
                $fixed = true;
            }
        }

        return $fixed ? implode("\n", $lines) : $content;
    }

    private function fixMalformedQueries($content)
    {
        // Fix common malformed SQL queries
        $patterns = [
            // Fix broken parameter placeholders in queries
            '/\'([^\']*)\$([0-9]+)([^\']*)\'/m' => function($matches) {
                $full = $matches[0];
                $before = $matches[1];
                $number = $matches[2];
                $after = $matches[3];
                
                // If this looks like a corrupted parameter, replace with ?
                if (empty($before) && empty($after)) {
                    return '?';
                } else {
                    return "'" . $before . $after . "'";
                }
            },
            
            // Fix double-quoted parameters
            '/"([^"]*)\$([0-9]+)([^"]*)"/m' => function($matches) {
                $full = $matches[0];
                $before = $matches[1];
                $number = $matches[2];
                $after = $matches[3];
                
                // If this looks like a corrupted parameter, replace with ?
                if (empty($before) && empty($after)) {
                    return '?';
                } else {
                    return '"' . $before . $after . '"';
                }
            }
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $content = preg_replace_callback($pattern, $replacement, $content);
            } else {
                $content = preg_replace($pattern, $replacement, $content);
            }
        }

        return $content;
    }

    private function hasSyntaxError($filePath)
    {
        $output = shell_exec("php -l \"$filePath\" 2>&1");
        return strpos($output, 'No syntax errors detected') === false;
    }

    private function showSummary()
    {
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "SYNTAX ERROR CHECK SUMMARY\n";
        echo str_repeat("=", 50) . "\n";
        
        echo "Files fixed: " . count($this->fixedFiles) . "\n";
        if (!empty($this->fixedFiles)) {
            foreach ($this->fixedFiles as $file) {
                echo "  ✓ $file\n";
            }
        }
        
        echo "\nFiles still with errors: " . count($this->errorFiles) . "\n";
        if (!empty($this->errorFiles)) {
            foreach ($this->errorFiles as $file) {
                echo "  ✗ $file\n";
            }
        }
        
        if (empty($this->fixedFiles) && empty($this->errorFiles)) {
            echo "No syntax errors found!\n";
        }
        
        echo "\nDone!\n";
    }
}

// Run the checker
$checker = new SyntaxErrorChecker();
$checker->run();
