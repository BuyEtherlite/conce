
<?php

namespace App\Http\Controllers;

use App\Models\CoreModule;
use App\Models\User;
use App\Models\Customer;
use App\Models\ServiceRequest;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Check if user has admin role for main application
        if (!in_array($user->role, ['admin', 'manager', 'staff']) && !$user->is_admin) {
            return redirect()->route('login')->with('error', 'Access denied. Admin privileges required.');
        }

        // Get available modules for the user
        $availableModules = $this->getUserAvailableModules($user);

        // Get dashboard statistics for regular admin portal
        try {
            // Get revenue data with fallback
            $totalRevenue = 0;
            $todayRevenue = 0;

            // Try to get revenue from various sources
            try {
                if (DB::getSchemaBuilder()->hasTable('revenue_collections')) {
                    $totalRevenue = DB::table('revenue_collections')->sum('amount') ?? 0;
                    $todayRevenue = DB::table('revenue_collections')
                        ->whereDate('created_at', today())
                        ->sum('amount') ?? 0;
                } elseif (DB::getSchemaBuilder()->hasTable('fiscal_receipts')) {
                    $totalRevenue = DB::table('fiscal_receipts')
                        ->where('status', 'completed')
                        ->sum('total_amount') ?? 0;
                    $todayRevenue = DB::table('fiscal_receipts')
                        ->where('status', 'completed')
                        ->whereDate('created_at', today())
                        ->sum('total_amount') ?? 0;
                } elseif (DB::getSchemaBuilder()->hasTable('finance_payments')) {
                    $totalRevenue = DB::table('finance_payments')
                        ->where('status', 'completed')
                        ->sum('amount') ?? 0;
                    $todayRevenue = DB::table('finance_payments')
                        ->where('status', 'completed')
                        ->whereDate('created_at', today())
                        ->sum('amount') ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning("Could not fetch revenue data: " . $e->getMessage());
            }

            $stats = [
                'total_users' => $this->safeCount('users'),
                'active_modules' => $this->safeCount('core_modules', ['is_active' => true]),
                'pending_requests' => $this->safeCount('service_requests', ['status' => 'pending']),
                'completed_requests' => $this->safeCount('service_requests', ['status' => 'completed']),
                'total_customers' => $this->safeCount('customers'),
                'total_departments' => $this->safeCount('departments'),
                'total_offices' => $this->safeCount('offices'),
                'total_revenue' => $totalRevenue,
                'today_revenue' => $todayRevenue,
                'active_citizens' => $this->safeCount('users', ['role' => 'citizen', 'active' => true]),
                'service_requests' => $this->safeCount('service_requests'),
                'pending_tasks' => $this->safeCount('service_requests', ['status' => 'pending']),
            ];

            // Get recent activities safely
            $recentActivities = $this->getRecentActivities();

        } catch (\Exception $e) {
            Log::error("Error fetching dashboard statistics: " . $e->getMessage());
            $stats = [
                'total_users' => 0,
                'active_modules' => 0,
                'pending_requests' => 0,
                'completed_requests' => 0,
                'total_customers' => 0,
                'total_departments' => 0,
                'total_offices' => 0,
                'total_revenue' => 0,
                'today_revenue' => 0,
                'active_citizens' => 0,
                'service_requests' => 0,
                'pending_tasks' => 0,
            ];
            $recentActivities = collect();
        }

        // Additional data for dashboard
        $activeModules = $availableModules ?? collect();
        $recentRequests = $this->getRecentServiceRequests();

        return view('dashboard.index', compact('stats', 'availableModules', 'user', 'activeModules', 'recentRequests', 'recentActivities'));
    }

    /**
     * Safely count records from a table with optional conditions
     */
    private function safeCount($table, $conditions = [])
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                return 0;
            }

            $query = DB::table($table);
            
            foreach ($conditions as $column => $value) {
                if (DB::getSchemaBuilder()->hasColumn($table, $column)) {
                    $query->where($column, $value);
                }
            }

            return $query->count();
        } catch (\Exception $e) {
            Log::warning("Could not count records from table {$table}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent activities safely
     */
    private function getRecentActivities()
    {
        try {
            $activities = collect();

            // Try to get recent service requests
            if (DB::getSchemaBuilder()->hasTable('service_requests')) {
                $recentRequests = DB::table('service_requests')
                    ->select('id', 'title', 'status', 'created_at', 'customer_id')
                    ->latest('created_at')
                    ->limit(5)
                    ->get();

                foreach ($recentRequests as $request) {
                    $activities->push((object)[
                        'type' => 'service_request',
                        'title' => $request->title ?? 'Service Request #' . $request->id,
                        'status' => $request->status ?? 'pending',
                        'created_at' => $request->created_at,
                        'customer_name' => $this->getCustomerName($request->customer_id)
                    ]);
                }
            }

            return $activities;
        } catch (\Exception $e) {
            Log::warning("Could not fetch recent activities: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get customer name safely
     */
    private function getCustomerName($customerId)
    {
        try {
            if (!$customerId || !DB::getSchemaBuilder()->hasTable('customers')) {
                return 'Unknown Customer';
            }

            $customer = DB::table('customers')
                ->select('name', 'first_name', 'last_name')
                ->where('id', $customerId)
                ->first();

            if (!$customer) {
                return 'Unknown Customer';
            }

            return $customer->name ?? ($customer->first_name . ' ' . $customer->last_name) ?? 'Customer #' . $customerId;
        } catch (\Exception $e) {
            return 'Unknown Customer';
        }
    }

    /**
     * Get recent service requests safely
     */
    private function getRecentServiceRequests()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('service_requests')) {
                return collect();
            }

            return DB::table('service_requests')
                ->select('id', 'title', 'status', 'created_at', 'customer_id')
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(function($request) {
                    return (object)[
                        'id' => $request->id,
                        'title' => $request->title ?? 'Service Request #' . $request->id,
                        'status' => $request->status ?? 'pending',
                        'created_at' => $request->created_at,
                        'customer' => (object)['name' => $this->getCustomerName($request->customer_id)]
                    ];
                });
        } catch (\Exception $e) {
            Log::warning("Could not fetch recent service requests: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get available modules for the current user
     */
    private function getUserAvailableModules($user)
    {
        $allModules = [
            'administration' => [
                'id' => 'administration',
                'name' => 'Administration CRM',
                'display_name' => 'Administration CRM',
                'icon' => 'users-cog',
                'description' => 'Customer relationship management and service delivery',
                'features' => ['Customer Services', 'Service Requests', 'Communications'],
                'route' => 'administration.crm.index',
                'is_active' => true,
                'is_core' => true
            ],
            'finance' => [
                'id' => 'finance',
                'name' => 'Finance Management',
                'display_name' => 'Finance Management',
                'icon' => 'dollar-sign',
                'description' => 'Financial management with accounting integration',
                'features' => ['General Ledger', 'Billing', 'Receipts', 'Accounting Integration'],
                'route' => 'finance.index',
                'is_active' => true,
                'is_core' => true
            ],
            'housing' => [
                'id' => 'housing',
                'name' => 'Housing Management',
                'display_name' => 'Housing Management',
                'icon' => 'home',
                'description' => 'Manage waiting lists, allocations, and housing records',
                'features' => ['Waiting List', 'Allocations', 'Properties'],
                'route' => 'housing.index',
                'is_active' => true,
                'is_core' => false
            ],
            'facilities' => [
                'id' => 'facilities',
                'name' => 'Facility Bookings',
                'display_name' => 'Facility Bookings',
                'icon' => 'swimming-pool',
                'description' => 'Manage bookings for pools, halls, and recreational facilities',
                'features' => ['Pool Bookings', 'Hall Rentals', 'Sports Facilities'],
                'route' => 'engineering.facilities.index',
                'is_active' => true,
                'is_core' => false
            ],
            'planning' => [
                'id' => 'planning',
                'name' => 'Town Planning',
                'display_name' => 'Town Planning',
                'icon' => 'building',
                'description' => 'Development applications and planning approvals',
                'features' => ['Applications', 'Approvals', 'Zoning'],
                'route' => 'planning.index',
                'is_active' => true,
                'is_core' => false
            ],
            'health' => [
                'id' => 'health',
                'name' => 'Health Services',
                'display_name' => 'Health Services',
                'icon' => 'heartbeat',
                'description' => 'Health permits, inspections, and environmental health',
                'features' => ['Health Permits', 'Inspections', 'Environmental Health'],
                'route' => 'health.index',
                'is_active' => true,
                'is_core' => false
            ],
            'licensing' => [
                'id' => 'licensing',
                'name' => 'Business Licensing',
                'display_name' => 'Business Licensing',
                'icon' => 'certificate',
                'description' => 'Business licenses and operating permits',
                'features' => ['Business Licenses', 'Operating Permits', 'Shop Permits'],
                'route' => 'licensing.index',
                'is_active' => true,
                'is_core' => false
            ],
            'utilities' => [
                'id' => 'utilities',
                'name' => 'Utilities Management',
                'display_name' => 'Utilities Management',
                'icon' => 'bolt',
                'description' => 'Electricity, gas, and infrastructure management',
                'features' => ['Electricity', 'Gas', 'Infrastructure'],
                'route' => 'utilities.index',
                'is_active' => true,
                'is_core' => false
            ],
            'water' => [
                'id' => 'water',
                'name' => 'Water Management',
                'display_name' => 'Water Management',
                'icon' => 'tint',
                'description' => 'Water connections, billing, and quality management',
                'features' => ['Connections', 'Billing', 'Quality Tests'],
                'route' => 'water.index',
                'is_active' => true,
                'is_core' => false
            ],
            'engineering' => [
                'id' => 'engineering',
                'name' => 'Engineering Services',
                'display_name' => 'Engineering Services',
                'icon' => 'tools',
                'description' => 'Infrastructure projects and maintenance',
                'features' => ['Projects', 'Infrastructure', 'Work Orders'],
                'route' => 'engineering.index',
                'is_active' => true,
                'is_core' => false
            ],
            'hr' => [
                'id' => 'hr',
                'name' => 'Human Resources',
                'display_name' => 'Human Resources',
                'icon' => 'user-tie',
                'description' => 'Employee management and payroll',
                'features' => ['Employees', 'Attendance', 'Payroll'],
                'route' => 'hr.index',
                'is_active' => true,
                'is_core' => false
            ],
            'committee' => [
                'id' => 'committee',
                'name' => 'Committee Management',
                'display_name' => 'Committee Management',
                'icon' => 'users',
                'description' => 'Committee meetings, agendas, and minutes',
                'features' => ['Meetings', 'Agendas', 'Minutes'],
                'route' => 'committee.index',
                'is_active' => true,
                'is_core' => false
            ]
        ];

        // Super admin gets access to all modules
        if ($user->role === 'admin' || $user->is_admin) {
            // Get modules using CoreModule model if table exists
            try {
                if (DB::getSchemaBuilder()->hasTable('core_modules')) {
                    $modules = DB::table('core_modules')
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('name', 'asc')
                        ->get();
                    
                    return $modules->map(function($module) {
                        return (object)[
                            'id' => $module->module_key,
                            'name' => $module->name,
                            'display_name' => $module->display_name ?? $module->name,
                            'icon' => $module->icon ?? 'cube',
                            'description' => $module->description ?? 'No description available',
                            'is_active' => $module->is_active,
                        ];
                    });
                }
            } catch (\Exception $e) {
                Log::warning("Could not fetch core modules: " . $e->getMessage());
            }

            // Fallback to static modules array - convert to objects with required methods
            $modules = [];
            foreach ($allModules as $module) {
                $modules[] = (object) $module;
            }
            return collect($modules);
        }

        // Filter modules based on department permissions and enabled status
        $accessibleModuleKeys = [];

        // Check if user has department access
        if ($user->department && isset($user->department->modules_access)) {
            foreach ($user->department->modules_access as $moduleKey) {
                if (isset($allModules[$moduleKey])) {
                    // Check if module is enabled in core modules
                    if ($this->isModuleEnabled($moduleKey)) {
                        $accessibleModuleKeys[] = $moduleKey;
                    }
                }
            }
        } else {
            // Default modules for users without departments
            $defaultModules = ['administration', 'finance'];
            foreach ($defaultModules as $moduleKey) {
                if (isset($allModules[$moduleKey]) && $this->isModuleEnabled($moduleKey)) {
                    $accessibleModuleKeys[] = $moduleKey;
                }
            }
        }

        // Get CoreModule instances for accessible modules
        if (!empty($accessibleModuleKeys)) {
            try {
                if (DB::getSchemaBuilder()->hasTable('core_modules')) {
                    return DB::table('core_modules')
                        ->whereIn('module_key', $accessibleModuleKeys)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('name', 'asc')
                        ->get()
                        ->map(function($module) {
                            return (object)[
                                'id' => $module->module_key,
                                'name' => $module->name,
                                'display_name' => $module->display_name ?? $module->name,
                                'icon' => $module->icon ?? 'cube',
                                'description' => $module->description ?? 'No description available',
                                'is_active' => $module->is_active,
                            ];
                        });
                }
            } catch (\Exception $e) {
                Log::warning("Could not fetch accessible modules: " . $e->getMessage());
            }

            // Fallback to static modules - convert to objects with required methods
            $accessibleModules = [];
            foreach ($accessibleModuleKeys as $key) {
                if (isset($allModules[$key])) {
                    $accessibleModules[] = (object) $allModules[$key];
                }
            }
            return collect($accessibleModules);
        }

        return collect();
    }

    /**
     * Check if a module is enabled
     */
    private function isModuleEnabled($moduleKey)
    {
        try {
            // Check if core_modules table exists
            if (!DB::getSchemaBuilder()->hasTable('core_modules')) {
                return true; // If table doesn't exist, assume all modules are enabled
            }

            $module = DB::table('core_modules')->where('module_key', $moduleKey)->first();

            if (!$module) {
                // If module doesn't exist in database, assume it's enabled
                return true;
            }

            return (bool) $module->is_active;
        } catch (\Exception $e) {
            Log::warning("Could not check module status for {$moduleKey}: " . $e->getMessage());
            return true; // Default to enabled if we can't check
        }
    }

    public function getStats()
    {
        $stats = [
            'users' => $this->safeCount('users'),
            'customers' => $this->safeCount('customers'),
            'service_requests' => $this->safeCount('service_requests'),
            'pending_requests' => $this->safeCount('service_requests', ['status' => 'pending']),
            'active_modules' => $this->safeCount('core_modules', ['is_active' => true]),
        ];

        return response()->json($stats);
    }
}
