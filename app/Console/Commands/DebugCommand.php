<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DebugAgent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DebugCommand extends Command
{
    protected $signature = 'debug:system {--save : Save debug report to file} {--fix : Attempt to fix common issues} {--deep : Deep analysis}';
    protected $description = 'Run comprehensive system debug analysis with auto-fix capabilities';

    public function handle()
    {
        $this->info('🔍 Starting comprehensive system debug...');

        $agent = new DebugAgent();
        $agent->enable();

        // Environment check
        $this->info('🌍 Checking environment...');
        $this->displayEnvironmentInfo();

        // Check installation status
        $this->info('📋 Checking installation status...');
        $installation = $agent->checkInstallation();
        $this->displayInstallationStatus($installation);

        // Check database connectivity
        $this->info('🗄️ Checking database status...');
        $database = $agent->checkDatabase();
        $this->displayDatabaseStatus($database);

        // Check file permissions
        $this->info('🔐 Checking file permissions...');
        $permissions = $agent->checkPermissions();
        $this->displayPermissions($permissions);

        // Test critical routes
        $this->info('🛣️ Testing critical routes...');
        $this->testCriticalRoutes($agent);

        // Check Laravel configuration
        $this->info('⚙️ Checking Laravel configuration...');
        $this->checkLaravelConfig();

        // Deep analysis if requested
        if ($this->option('deep')) {
            $this->info('🔬 Running deep analysis...');
            $this->deepAnalysis();
        }

        // Auto-fix if requested
        if ($this->option('fix')) {
            $this->info('🔧 Attempting to fix common issues...');
            $this->autoFix();
        }

        if ($this->option('save')) {
            $path = $agent->saveReport();
            $this->info("📄 Debug report saved to: {$path}");
        }

        $this->info('✅ Debug analysis complete!');
        $this->displayRecommendations();
    }

    private function displayEnvironmentInfo()
    {
        $this->table(['Property', 'Value'], [
            ['PHP Version', PHP_VERSION],
            ['Laravel Version', app()->version()],
            ['Environment', app()->environment()],
            ['Debug Mode', config('app.debug') ? 'ON' : 'OFF'],
            ['App Key Set', config('app.key') ? 'YES' : 'NO'],
            ['Database Driver', config('database.default')],
            ['Cache Driver', config('cache.default')],
            ['Session Driver', config('session.driver')],
        ]);
    }

    private function displayInstallationStatus($status)
    {
        foreach ($status as $check => $result) {
            $icon = $result ? '✅' : '❌';
            $this->line("  {$icon} {$check}: " . ($result ? 'OK' : 'FAIL'));
        }
    }

    private function displayDatabaseStatus($status)
    {
        $icon = $status['connection'] ? '✅' : '❌';
        $this->line("  {$icon} Database Connection: " . ($status['connection'] ? 'OK' : 'FAIL'));

        if ($status['error']) {
            $this->error("  Error: {$status['error']}");
        }

        if (!empty($status['tables'])) {
            $this->info("  📊 Found " . count($status['tables']) . " tables");
            if ($this->option('deep')) {
                $this->line("  Tables: " . implode(', ', array_slice($status['tables'], 0, 10)) . (count($status['tables']) > 10 ? '...' : ''));
            }
        }

        if (!empty($status['migrations'])) {
            $this->info("  🔄 Found " . count($status['migrations']) . " migrations");
        }
    }

    private function displayPermissions($permissions)
    {
        foreach ($permissions as $name => $info) {
            $readable = $info['readable'] ? '✅' : '❌';
            $writable = $info['writable'] ? '✅' : '❌';
            $exists = $info['exists'] ? '✅' : '❌';
            $this->line("  {$name}: E{$exists} R{$readable} W{$writable} ({$info['permissions']})");
        }
    }

    private function testCriticalRoutes($agent)
    {
        $criticalRoutes = [
            'dashboard',
            'administration.crm.index',
            'finance.index',
            'housing.index',
            'committee.index',
            'login'
        ];

        foreach ($criticalRoutes as $route) {
            $result = $agent->testRoute($route);
            $icon = $result['exists'] ? '✅' : '❌';
            $this->line("  {$icon} {$route}: " . ($result['exists'] ? 'OK' : 'MISSING'));

            if (!$result['exists'] && isset($result['error'])) {
                $this->error("    Error: {$result['error']}");
            }
        }
    }

    private function checkLaravelConfig()
    {
        $configs = [
            'APP_URL' => config('app.url'),
            'DB_CONNECTION' => config('database.default'),
            'CACHE_DRIVER' => config('cache.default'),
            'SESSION_DRIVER' => config('session.driver'),
            'MAIL_MAILER' => config('mail.default'),
        ];

        foreach ($configs as $key => $value) {
            $this->line("  {$key}: {$value}");
        }
    }

    private function deepAnalysis()
    {
        // Check for common Laravel issues
        $this->info('  🔍 Checking for common issues...');

        // Check if routes are cached
        if (file_exists(base_path('bootstrap/cache/routes-v7.php'))) {
            $this->warn('  ⚠️ Routes are cached - may need clearing');
        }

        // Check if config is cached
        if (file_exists(base_path('bootstrap/cache/config.php'))) {
            $this->warn('  ⚠️ Config is cached - may need clearing');
        }

        // Check composer autoload
        if (!file_exists(base_path('vendor/autoload.php'))) {
            $this->error('  ❌ Composer dependencies not installed');
        }

        // Check for .env file
        if (!file_exists(base_path('.env'))) {
            $this->error('  ❌ .env file missing');
        }
    }

    private function autoFix()
    {
        $this->info('  🔧 Clearing caches...');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $this->info('  🔧 Optimizing autoloader...');
        if (file_exists(base_path('composer.json'))) {
            exec('composer dump-autoload -o 2>&1', $output, $exitCode);
            if ($exitCode === 0) {
                $this->info('  ✅ Autoloader optimized');
            } else {
                $this->warn('  ⚠️ Failed to optimize autoloader');
            }
        }
    }

    private function displayRecommendations()
    {
        $this->info('💡 Recommendations:');
        $this->line('  - Run with --fix to attempt automatic fixes');
        $this->line('  - Run with --deep for detailed analysis');
        $this->line('  - Run with --save to save detailed report');
        $this->line('  - Check logs in storage/logs/ for more details');
    }
}