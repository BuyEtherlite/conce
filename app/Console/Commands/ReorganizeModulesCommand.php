<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ReorganizeModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reorganize-modules {--dry-run : Show what would be moved without actually moving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reorganize existing code into modular structure';

    /**
     * Module mapping for reorganization
     */
    protected $moduleMapping = [
        'Finance' => [
            'controllers' => ['Finance'],
            'models' => ['Finance'],
            'views' => ['finance'],
            'prefix' => 'finance'
        ],
        'Housing' => [
            'controllers' => ['Housing'],
            'models' => ['Housing'],
            'views' => ['housing'],
            'prefix' => 'housing'
        ],
        'Water' => [
            'controllers' => ['Water'],
            'models' => ['Water'],
            'views' => ['water'],
            'prefix' => 'water'
        ],
        'Committee' => [
            'controllers' => ['Committee'],
            'models' => ['Committee'],
            'views' => ['committee'],
            'prefix' => 'committee'
        ],
        'Administration' => [
            'controllers' => ['Administration', 'Admin'],
            'models' => [],
            'views' => ['administration', 'admin'],
            'prefix' => 'administration'
        ],
        'Health' => [
            'controllers' => ['Health'],
            'models' => ['Health'],
            'views' => ['health'],
            'prefix' => 'health'
        ],
        'Licensing' => [
            'controllers' => ['Licensing'],
            'models' => ['Licensing'],
            'views' => ['licensing'],
            'prefix' => 'licensing'
        ],
        'Parking' => [
            'controllers' => ['Parking'],
            'models' => ['Parking'],
            'views' => ['parking'],
            'prefix' => 'parking'
        ],
        'Markets' => [
            'controllers' => ['Markets'],
            'models' => ['Markets'],
            'views' => ['markets'],
            'prefix' => 'markets'
        ],
        'Inventory' => [
            'controllers' => ['Inventory'],
            'models' => ['Inventory'],
            'views' => ['inventory'],
            'prefix' => 'inventory'
        ],
        'HR' => [
            'controllers' => ['HR', 'Hr'],
            'models' => ['HR', 'Hr'],
            'views' => ['hr'],
            'prefix' => 'hr'
        ],
        'Engineering' => [
            'controllers' => ['Engineering'],
            'models' => ['Engineering'],
            'views' => ['engineering'],
            'prefix' => 'engineering'
        ],
        'Property' => [
            'controllers' => ['Property'],
            'models' => ['Property'],
            'views' => ['property'],
            'prefix' => 'property'
        ],
        'Utilities' => [
            'controllers' => ['Utilities'],
            'models' => ['Utilities'],
            'views' => ['utilities'],
            'prefix' => 'utilities'
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Starting module reorganization...');

        foreach ($this->moduleMapping as $module => $config) {
            $this->info("\nProcessing module: {$module}");
            
            $this->createModuleStructure($module, $dryRun);
            $this->moveControllers($module, $config['controllers'], $dryRun);
            $this->moveModels($module, $config['models'], $dryRun);
            $this->moveViews($module, $config['views'], $dryRun);
            $this->createModuleRoutes($module, $config['prefix'], $dryRun);
        }

        if ($dryRun) {
            $this->warn('This was a dry run. No files were actually moved.');
        } else {
            $this->info('Module reorganization completed successfully!');
        }
    }

    /**
     * Create module directory structure
     */
    protected function createModuleStructure(string $module, bool $dryRun): void
    {
        $directories = [
            "app/Modules/{$module}",
            "app/Modules/{$module}/Controllers",
            "app/Modules/{$module}/Models",
            "app/Modules/{$module}/Views",
            "app/Modules/{$module}/Routes",
            "app/Modules/{$module}/Services",
            "app/Modules/{$module}/Requests",
            "app/Modules/{$module}/Resources",
            "app/Modules/{$module}/Migrations"
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                if ($dryRun) {
                    $this->line("Would create directory: {$directory}");
                } else {
                    File::makeDirectory($directory, 0755, true);
                    $this->line("Created directory: {$directory}");
                }
            }
        }
    }

    /**
     * Move controllers to module
     */
    protected function moveControllers(string $module, array $controllerNamespaces, bool $dryRun): void
    {
        foreach ($controllerNamespaces as $namespace) {
            $sourcePath = app_path("Http/Controllers/{$namespace}");
            $targetPath = app_path("Modules/{$module}/Controllers");

            if (File::exists($sourcePath)) {
                if (File::isDirectory($sourcePath)) {
                    $files = File::allFiles($sourcePath);
                    foreach ($files as $file) {
                        $relativePath = $file->getRelativePathname();
                        $targetFile = "{$targetPath}/{$relativePath}";

                        if ($dryRun) {
                            $this->line("Would move: {$file->getPathname()} -> {$targetFile}");
                        } else {
                            File::ensureDirectoryExists(dirname($targetFile));
                            File::move($file->getPathname(), $targetFile);
                            $this->updateNamespace($targetFile, $namespace, $module);
                            $this->line("Moved: {$file->getPathname()} -> {$targetFile}");
                        }
                    }
                }
            }
        }
    }

    /**
     * Move models to module
     */
    protected function moveModels(string $module, array $modelNamespaces, bool $dryRun): void
    {
        foreach ($modelNamespaces as $namespace) {
            $sourcePath = app_path("Models/{$namespace}");
            $targetPath = app_path("Modules/{$module}/Models");

            if (File::exists($sourcePath)) {
                if (File::isDirectory($sourcePath)) {
                    $files = File::allFiles($sourcePath);
                    foreach ($files as $file) {
                        $relativePath = $file->getRelativePathname();
                        $targetFile = "{$targetPath}/{$relativePath}";

                        if ($dryRun) {
                            $this->line("Would move: {$file->getPathname()} -> {$targetFile}");
                        } else {
                            File::ensureDirectoryExists(dirname($targetFile));
                            File::move($file->getPathname(), $targetFile);
                            $this->updateNamespace($targetFile, "Models/{$namespace}", "Modules/{$module}/Models");
                            $this->line("Moved: {$file->getPathname()} -> {$targetFile}");
                        }
                    }
                }
            }
        }
    }

    /**
     * Move views to module
     */
    protected function moveViews(string $module, array $viewDirectories, bool $dryRun): void
    {
        foreach ($viewDirectories as $viewDir) {
            $sourcePath = resource_path("views/{$viewDir}");
            $targetPath = app_path("Modules/{$module}/Views");

            if (File::exists($sourcePath)) {
                if ($dryRun) {
                    $this->line("Would move views: {$sourcePath} -> {$targetPath}");
                } else {
                    File::ensureDirectoryExists($targetPath);
                    File::copyDirectory($sourcePath, $targetPath);
                    $this->line("Moved views: {$sourcePath} -> {$targetPath}");
                }
            }
        }
    }

    /**
     * Create module routes
     */
    protected function createModuleRoutes(string $module, string $prefix, bool $dryRun): void
    {
        $webRoutesPath = app_path("Modules/{$module}/Routes/web.php");
        $apiRoutesPath = app_path("Modules/{$module}/Routes/api.php");

        if ($dryRun) {
            $this->line("Would create routes: {$webRoutesPath}");
            $this->line("Would create routes: {$apiRoutesPath}");
            return;
        }

        // Create web routes
        if (!File::exists($webRoutesPath)) {
            $webRoutes = $this->generateWebRoutes($module, $prefix);
            File::put($webRoutesPath, $webRoutes);
            $this->line("Created web routes: {$webRoutesPath}");
        }

        // Create API routes
        if (!File::exists($apiRoutesPath)) {
            $apiRoutes = $this->generateApiRoutes($module, $prefix);
            File::put($apiRoutesPath, $apiRoutes);
            $this->line("Created API routes: {$apiRoutesPath}");
        }
    }

    /**
     * Update namespace in moved files
     */
    protected function updateNamespace(string $filePath, string $oldNamespace, string $newNamespace): void
    {
        $content = File::get($filePath);
        $oldNamespacePath = "App\\Http\\Controllers\\{$oldNamespace}";
        $newNamespacePath = "App\\Modules\\{$newNamespace}\\Controllers";

        if (strpos($content, "App\\Models\\{$oldNamespace}") !== false) {
            $oldNamespacePath = "App\\Models\\{$oldNamespace}";
            $newNamespacePath = "App\\Modules\\{$newNamespace}\\Models";
        }

        $content = str_replace($oldNamespacePath, $newNamespacePath, $content);
        File::put($filePath, $content);
    }

    /**
     * Generate web routes template
     */
    protected function generateWebRoutes(string $module, string $prefix): string
    {
        return "<?php

use Illuminate\\Support\\Facades\\Route;
use App\\Modules\\{$module}\\Controllers\\{$module}Controller;

/*
|--------------------------------------------------------------------------
| {$module} Module Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [{$module}Controller::class, 'index'])->name('index');
Route::get('/dashboard', [{$module}Controller::class, 'dashboard'])->name('dashboard');

// Add your {$prefix} specific routes here
";
    }

    /**
     * Generate API routes template
     */
    protected function generateApiRoutes(string $module, string $prefix): string
    {
        return "<?php

use Illuminate\\Support\\Facades\\Route;
use App\\Modules\\{$module}\\Controllers\\{$module}ApiController;

/*
|--------------------------------------------------------------------------
| {$module} Module API Routes
|--------------------------------------------------------------------------
*/

Route::get('/stats', [{$module}ApiController::class, 'getStats'])->name('stats');

// Add your {$prefix} API routes here
";
    }
}
