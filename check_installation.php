<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load environment variables
if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Set up database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_DATABASE'] ?? 'council_erp',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔍 Checking ERP Installation Status...\n\n";

// Check database connection
try {
    $pdo = $capsule->getConnection()->getPdo();
    echo "✅ Database connection: OK\n";
} catch (Exception $e) {
    echo "❌ Database connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if installation is complete
$installedFile = 'storage/app/installed.lock';
if (file_exists($installedFile)) {
    echo "✅ Installation lock file: EXISTS\n";
} else {
    echo "⚠️  Installation lock file: MISSING\n";
}

// Check key tables
$keyTables = [
    'users',
    'councils',
    'departments',
    'hr_employees',
    'hr_attendance',
    'municipal_services',
    'finance_chart_of_accounts',
    'survey_projects'
];

echo "\n📋 Checking key tables:\n";
foreach ($keyTables as $table) {
    try {
        $exists = Capsule::schema()->hasTable($table);
        if ($exists) {
            $count = Capsule::table($table)->count();
            echo "✅ {$table}: EXISTS ({$count} records)\n";
        } else {
            echo "❌ {$table}: MISSING\n";
        }
    } catch (Exception $e) {
        echo "⚠️  {$table}: ERROR - " . $e->getMessage() . "\n";
    }
}

// Check for migrations table
echo "\n🔄 Checking migrations:\n";
try {
    if (Capsule::schema()->hasTable('migrations')) {
        $migrationCount = Capsule::table('migrations')->count();
        echo "✅ Migrations table: EXISTS ({$migrationCount} migrations run)\n";
        
        // Show recent migrations
        $recentMigrations = Capsule::table('migrations')
            ->orderBy('batch', 'desc')
            ->limit(5)
            ->get();
            
        echo "\n📝 Recent migrations:\n";
        foreach ($recentMigrations as $migration) {
            echo "   - {$migration->migration} (batch {$migration->batch})\n";
        }
    } else {
        echo "❌ Migrations table: MISSING\n";
    }
} catch (Exception $e) {
    echo "⚠️  Migrations check: ERROR - " . $e->getMessage() . "\n";
}

// Check storage permissions
echo "\n📁 Checking storage permissions:\n";
$storageDirectories = [
    'storage/app',
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views'
];

foreach ($storageDirectories as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "✅ {$dir}: WRITABLE\n";
    } else {
        echo "❌ {$dir}: NOT WRITABLE OR MISSING\n";
    }
}

echo "\n🎯 Installation Check Complete!\n";
?>
