<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Council;

class InstallController extends Controller
{
    public function index()
    {
        // Check if system is already installed
        if ($this->isInstalled()) {
            return redirect('/dashboard')->with('message', 'System is already installed.');
        }

        // Ensure basic environment is set up
        $this->ensureBasicEnvironment();
        $this->ensureStorageDirectories();

        // Get system requirements check
        $requirements = $this->checkSystemRequirements();
        $permissions = $this->checkPermissions();

        return view('install.index', compact('requirements', 'permissions'));
    }

    public function store(Request $request)
    {
        try {
            // Use SQLite for simplicity on Replit
            $dbPath = '/tmp/database.sqlite';

            // Create SQLite database file if it doesn't exist
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }

            // Update .env file
            $envFile = base_path('.env');
            $envContent = file_get_contents($envFile);
            // Ensure DB_CONNECTION is set to sqlite if using SQLite
            $envContent = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=sqlite', $envContent);
            $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE={$dbPath}", $envContent);
            // Clear other DB settings to avoid conflicts if they were previously set
            $envContent = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST=', $envContent);
            $envContent = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT=', $envContent);
            $envContent = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=', $envContent);
            $envContent = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=', $envContent);
            file_put_contents($envFile, $envContent);

            // Clear config cache to reload database settings
            Artisan::call('config:clear');
            Artisan::call('cache:clear'); // Also clear cache

            // Force reload the configuration for SQLite
            $app = app();
            $app->make('config')->set('database.default', 'sqlite');
            $app->make('config')->set('database.connections.sqlite.database', $dbPath);


            // Run migrations and seeders
            \Log::info('Starting database migrations and seeding...');
            try {
                $exitCodeMigrations = Artisan::call('migrate', [
                    '--force' => true,
                    '--database' => 'sqlite'
                ]);
            } catch (\Exception $e) {
                 // If migrate fails, try wiping and migrating (though for new SQLite it's unlikely)
                \Log::warning('Migration failed, trying wipe and migrate: ' . $e->getMessage());
                Artisan::call('db:wipe', ['--force' => true, '--database' => 'sqlite']);
                $exitCodeMigrations = Artisan::call('migrate', [
                    '--force' => true,
                    '--database' => 'sqlite'
                ]);
            }

             if ($exitCodeMigrations !== 0) {
                $output = Artisan::output();
                \Log::error('Migration output: ' . $output);
                throw new \Exception('Migration failed: ' . $output);
            }

            $exitCodeSeeders = Artisan::call('db:seed', ['--force' => true]);
            if ($exitCodeSeeders !== 0) {
                $output = Artisan::output();
                \Log::error('Seeder output: ' . $output);
                throw new \Exception('Seeder failed: ' . $output);
            }
            \Log::info('Database migrations and seeding completed successfully');

            // Update super admin if provided
            if ($request->admin_email) {
                DB::beginTransaction();
                try {
                    $user = User::where('email', 'admin@council.gov')->first();
                    if ($user) {
                        $user->update([
                            'first_name' => $request->admin_first_name ?? 'Super',
                            'last_name' => $request->admin_last_name ?? 'Admin',
                            'email' => $request->admin_email,
                            'username' => $request->admin_username ?? 'superadmin',
                            'password' => Hash::make($request->admin_password ?? 'admin123'),
                            'role' => 'admin',
                            'is_active' => true,
                            'email_verified_at' => now(),
                        ]);
                    } else {
                        // If admin@council.gov doesn't exist, create a new user
                        User::create([
                            'first_name' => $request->admin_first_name ?? 'Super',
                            'last_name' => $request->admin_last_name ?? 'Admin',
                            'email' => $request->admin_email,
                            'username' => $request->admin_username ?? 'superadmin',
                            'password' => Hash::make($request->admin_password ?? 'admin123'),
                            'role' => 'admin',
                            'is_active' => true,
                            'email_verified_at' => now(),
                        ]);
                    }
                    DB::commit();
                    \Log::info('Admin user updated/created successfully.');
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e; // Re-throw to be caught by the outer catch block
                }
            } else {
                 // If no admin email is provided, ensure the default admin user exists with default credentials if it was created by seeders
                 // If seeders don't create it, this part might need adjustment depending on seeder logic.
                 // For now, assuming seeders might create a default user. If not, this block would need to create one.
                 \Log::info('No admin email provided, skipping admin update.');
            }

            // Create installation lock file
            $this->markInstallationComplete();
            \Log::info('Installation completed successfully.');

            return redirect()->route('install.complete')->with('success', 'Installation completed successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to be caught and handled properly
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Installation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Clean up any partially created files/state if installation fails critically
            if (file_exists(base_path('.env'))) {
                // Potentially revert .env if issues occur, or leave as is for debugging
            }
            if (file_exists(storage_path('app/installed.lock'))) {
                unlink(storage_path('app/installed.lock'));
            }

            return back()->withErrors(['installation' => 'Installation failed: ' . $e->getMessage()])->withInput();
        }
    }


    public function testDatabase(Request $request)
    {
        try {
            // This method might not be directly applicable for SQLite setup in this context,
            // as the database file is created and configured within the store method.
            // However, we can simulate a check for the existence of the .env file and its basic structure.
            $envFile = base_path('.env');
            if (!file_exists($envFile)) {
                 return response()->json([
                    'success' => false,
                    'message' => '.env file not found. Please ensure it is created.'
                ]);
            }

            $envContent = file_get_contents($envFile);
            $dbConnection = preg_match('/^DB_CONNECTION=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';

            if ($dbConnection === 'sqlite') {
                $dbPath = preg_match('/^DB_DATABASE=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';
                if (!empty($dbPath) && file_exists($dbPath)) {
                    return response()->json([
                        'success' => true,
                        'message' => 'SQLite database file found and .env configured.'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'SQLite database file not found at the specified path in .env.'
                    ]);
                }
            } elseif ($dbConnection === 'mysql') {
                 // Fallback to original MySQL test if needed, though not the primary Replit setup
                $dbHost = preg_match('/^DB_HOST=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';
                $dbPort = preg_match('/^DB_PORT=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';
                $dbName = preg_match('/^DB_DATABASE=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';
                $dbUser = preg_match('/^DB_USERNAME=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';
                $dbPass = preg_match('/^DB_PASSWORD=(.*)$/m', $envContent, $matches) ? ($matches[1] ?? '') : '';

                if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'MySQL database configuration in .env is incomplete.'
                    ]);
                }

                try {
                    $dsn = "mysql:host={$dbHost}" . ($dbPort ? ";port={$dbPort}" : '');
                    $testConnection = new \PDO($dsn, $dbUser, $dbPass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
                    $testConnection->exec("USE `{$dbName}`;"); // Check if database can be selected
                    return response()->json([
                        'success' => true,
                        'message' => 'MySQL database connection successful!'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'MySQL database connection failed: ' . $e->getMessage()
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported or missing DB_CONNECTION in .env file.'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("Error in testDatabase: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during database testing: ' . $e->getMessage()
            ]);
        }
    }

    public function complete()
    {
        if (!$this->isInstalled()) {
            return redirect('/install');
        }

        // Fetch admin user based on the role, or a common identifier if role isn't guaranteed after installation
        $admin = User::where('role', 'admin')->first();

        // If admin role wasn't set or found, try to find by email if it was provided during installation
        if (!$admin && session('admin_email')) {
             $admin = User::where('email', session('admin_email'))->first();
        }

        // As a last resort, try to find any user if the above fail, though less ideal
        if (!$admin) {
             $admin = User::first(); // Fallback to the first user found
        }


        if (!$admin) {
            // This case should ideally not happen if installation was successful and created a user
            \Log::error("Admin user not found after installation completion check.");
            return redirect('/install')->withErrors(['error' => 'Admin user not found. Please re-run installation.']);
        }

        return view('install.complete', compact('admin'));
    }

    private function testDatabaseConnection($request)
    {
        // This method is retained for completeness but the primary logic for Replit/SQLite is in store() and testDatabase()
        // If using other databases, this would be the place to test connection.
        // For the SQLite setup on Replit, the testDatabase() method handles the check.
        // The current store() method directly configures and then attempts migrations.
        // To make this method more robust for other DBs, it should be used before updating .env.

        $dbConnection = $request->get('db_connection', config('database.default', 'mysql'));

        if ($dbConnection === 'mysql') {
            try {
                // First test if we can connect to MySQL server
                $dsn = "mysql:host={$request->db_host};port={$request->db_port}";
                $testConnection = new \PDO($dsn, $request->db_username, $request->db_password);
                $testConnection = null;

                // Then test if database exists, create if not
                $connectionConfig = [
                    'driver' => 'mysql',
                    'host' => $request->db_host,
                    'port' => $request->db_port,
                    'database' => $request->db_database,
                    'username' => $request->db_username,
                    'password' => $request->db_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null,
                    'options' => [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    ],
                ];

                config(['database.connections.test_connection' => $connectionConfig]);
                DB::connection('test_connection')->getPdo();

                // Try to create database if it doesn't exist
                try {
                    DB::connection('test_connection')->statement("CREATE DATABASE IF NOT EXISTS `{$request->db_database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                } catch (\Exception $e) {
                    \Log::info('Database creation attempted: ' . $e->getMessage());
                }

                DB::purge('test_connection');
                return true; // Indicate success
            } catch (\Exception $e) {
                throw new \Exception('Database connection failed: ' . $e->getMessage());
            }
        } elseif ($dbConnection === 'sqlite') {
            $dbPath = $request->get('db_database', env('DB_DATABASE'));
            if (empty($dbPath)) {
                throw new \Exception('Database path is not specified for SQLite.');
            }
            try {
                // Check if the directory for the SQLite file exists
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    if (!@mkdir($dir, 0755, true)) {
                        throw new \Exception("Failed to create directory for SQLite database: {$dir}");
                    }
                }
                // Check if the file can be created/accessed
                if (!file_put_contents($dbPath, '') !== false) {
                    throw new \Exception("Cannot write to SQLite database file path: {$dbPath}");
                }
                return true; // Indicate success
            } catch (\Exception $e) {
                throw new \Exception('SQLite database file check failed: ' . $e->getMessage());
            }
        }
        // Add checks for other database types if necessary
        throw new \Exception("Unsupported database connection type: {$dbConnection}");
    }

    private function isInstalled()
    {
        return file_exists(storage_path('app/installed.lock'));
    }

    private function markInstallationComplete()
    {
        $installData = [
            'completed_at' => now()->toISOString(),
            'version' => '1.0.0', // Consider updating this if you have versioning
            'installation_id' => Str::uuid(),
            'domain' => request()->getHost()
        ];

        // Ensure the storage/app directory exists
        $storageAppPath = storage_path('app');
        if (!is_dir($storageAppPath)) {
            if (!@mkdir($storageAppPath, 0755, true)) {
                \Log::error("Failed to create storage/app directory during installation completion.");
                // Depending on severity, you might want to throw an exception here.
                return false;
            }
        }

        // Write the lock file
        if (file_put_contents(storage_path('app/installed.lock'), json_encode($installData, JSON_PRETTY_PRINT)) === false) {
            \Log::error("Failed to write to installed.lock file.");
            return false;
        }
        return true;
    }


    private function ensureBasicEnvironment()
    {
        $envFile = base_path('.env');

        if (!file_exists($envFile)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envFile);
                \Log::info('.env file copied from .env.example');
            } else {
                \Log::info('.env.example not found, creating a default .env file.');
                // Default content for a new .env file
                $envContent = "APP_NAME=\"Council ERP\"\n";
                $envContent .= "APP_ENV=production\n"; // Default to production for security
                $envContent .= "APP_DEBUG=false\n";   // Default to false for security
                $envContent .= "APP_KEY=\n";         // Will be generated below
                $envContent .= "APP_URL=http://localhost\n\n"; // Default URL, user should change it

                $envContent .= "DB_CONNECTION=sqlite\n"; // Default to SQLite for Replit/simplicity
                $envContent .= "DB_HOST=\n";           // Not needed for SQLite
                $envContent .= "DB_PORT=\n";           // Not needed for SQLite
                $envContent .= "DB_DATABASE=/tmp/database.sqlite\n"; // Default path for SQLite
                $envContent .= "DB_USERNAME=\n";       // Not needed for SQLite
                $envContent .= "DB_PASSWORD=\n\n";     // Not needed for SQLite

                $envContent .= "SESSION_DRIVER=file\n";
                $envContent .= "SESSION_LIFETIME=120\n";

                file_put_contents($envFile, $envContent);
            }
        }

        // Generate APP_KEY if it's missing or empty
        $env = file_get_contents($envFile);
        if (preg_match('/^APP_KEY=$/m', $env) || preg_match('/^APP_KEY=\s*$/m', $env)) {
            try {
                $key = 'base64:' . base64_encode(random_bytes(32));
                $env = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $env);
                file_put_contents($envFile, $env);
                \Log::info('APP_KEY generated and saved to .env');
            } catch (\Exception $e) {
                \Log::warning('Failed to generate APP_KEY: ' . $e->getMessage());
                // Optionally throw an exception if APP_KEY is critical
            }
        }
    }

    private function ensureStorageDirectories()
    {
        $directories = [
            'storage/framework/sessions',
            'storage/framework/cache/data',
            'storage/framework/views',
            'storage/app/public',
            'storage/logs',
            'bootstrap/cache'
        ];

        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            if (!is_dir($fullPath)) {
                // Attempt to create directory and set permissions
                if (!@mkdir($fullPath, 0755, true)) {
                    \Log::warning("Failed to create directory: {$fullPath}");
                    // Depending on the OS and permissions, this might fail.
                    // For critical directories, consider throwing an exception.
                } else {
                    // Explicitly set permissions again if creation was successful
                    @chmod($fullPath, 0755);
                }
            } else {
                // Ensure existing directories also have correct permissions
                @chmod($fullPath, 0755);
            }
        }
    }

    private function updateEnvironmentFile($request)
    {
        $envFile = base_path('.env');
        $this->ensureBasicEnvironment(); // Ensure .env exists before trying to update

        $env = file_get_contents($envFile);

        // Update app settings
        $env = preg_replace('/^APP_NAME=.*$/m', 'APP_NAME="' . addslashes($request->site_name) . '"', $env);
        // Using the provided APP_URL from request or a default if not provided.
        // Note: The original code had a hardcoded URL, which might be incorrect for user deployment.
        // It's better to use the request URL or a user-provided value if available.
        $appUrl = $request->site_url ?? ($request->getHost() ? (request()->isSecure() ? 'https://' : 'http://') . request()->getHost() : 'http://localhost');
        $env = preg_replace('/^APP_URL=.*$/m', 'APP_URL=' . $appUrl, $env);


        // Update database settings - only if they are provided and relevant
        // This logic assumes we might be switching between DB types, so it's careful.
        // For the specific Replit/SQLite case, we directly set SQLite configs in store().
        // This method remains to handle potential future needs or different deployment scenarios.

        if ($request->has('db_host')) {
            $env = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST=' . $request->db_host, $env);
        }
        if ($request->has('db_port')) {
            $env = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT=' . $request->db_port, $env);
        }
        if ($request->has('db_database')) {
            $env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=' . $request->db_database, $env);
        }
        if ($request->has('db_username')) {
            $env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=' . $request->db_username, $env);
        }
        if ($request->has('db_password')) {
            $env = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=' . $request->db_password, $env);
        }
        // Explicitly set DB_CONNECTION if it's being managed by the installer for a specific type
        // For example, if the installer is specifically setting up MySQL:
        if ($request->has('db_connection') && in_array($request->db_connection, ['mysql', 'pgsql', 'sqlite', 'sqlsrv'])) {
             $env = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=' . $request->db_connection, $env);
        }


        file_put_contents($envFile, $env);
         \Log::info('Environment file updated.');
    }

    private function checkSystemRequirements()
    {
        $requirements = [];

        // PHP Version Check
        $phpVersion = PHP_VERSION;
        $requirements[] = [
            'name' => 'PHP Version >= 8.1',
            'status' => version_compare($phpVersion, '8.1.0', '>='),
            'current' => $phpVersion
        ];

        // PHP Extension Checks
        $extensions = [
            'openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath',
            'fileinfo', // Often needed for file uploads and MIME type detection
            'intl'      // For internationalization features
        ];
        foreach ($extensions as $extension) {
            $isLoaded = extension_loaded($extension);
            $requirements[] = [
                'name' => 'PHP Extension: ' . $extension,
                'status' => $isLoaded,
                'current' => $isLoaded ? 'Loaded' : 'Missing'
            ];
        }

        // Check for common writable directories
        $writablePaths = [
            'storage/',
            'bootstrap/cache/',
        ];
        foreach ($writablePaths as $path) {
            $fullPath = base_path($path);
            $isWritable = is_writable($fullPath);
            $requirements[] = [
                'name' => 'Directory Writable: ' . $path,
                'status' => $isWritable,
                'current' => $isWritable ? 'Writable' : 'Not Writable'
            ];
        }

        return $requirements;
    }

    private function checkPermissions()
    {
        // This function seems redundant with checkSystemRequirements.
        // Renaming or merging might be beneficial.
        // Keeping original structure for now but pointing out potential overlap.
        $permissions = [];
        $paths = [
            'storage/framework/',
            'storage/logs/',
            'bootstrap/cache/',
        ];

        foreach ($paths as $path) {
            $fullPath = base_path($path);
            $isWritable = is_writable($fullPath);
            $permissions[] = [
                'name' => $path,
                'status' => $isWritable,
                'current' => $isWritable ? 'Writable' : 'Not Writable'
            ];
        }
        return $permissions;
    }
}