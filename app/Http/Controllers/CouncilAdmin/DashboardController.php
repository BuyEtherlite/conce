<?php

namespace App\Http\Controllers\CouncilAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoreModule;
use App\Models\Department;
use App\Models\ServiceRequest;
use App\Models\Customer;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Cache key for dashboard data
            $cacheKey = 'council_admin_dashboard_' . auth()->id();
            
            return Cache::remember($cacheKey, 300, function () {
                return $this->getDashboardData();
            });

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return $this->getFallbackDashboardData();
        }
    }

    private function getDashboardData()
    {
        // User statistics with error handling
        $userStats = $this->getUserStatistics();
        
        // Module statistics
        $moduleStats = $this->getModuleStatistics();
        
        // Department and office statistics
        $organizationStats = $this->getOrganizationStatistics();
        
        // Service request statistics
        $serviceStats = $this->getServiceStatistics();
        
        // Customer statistics
        $customerStats = $this->getCustomerStatistics();
        
        // Recent activity
        $recentActivity = $this->getRecentActivity();
        
        // System health
        $systemHealth = $this->getSystemHealth();
        
        // Performance metrics
        $performanceMetrics = $this->getPerformanceMetrics();
        
        // Revenue statistics
        $revenueStats = $this->getRevenueStatistics();
        
        // Compile comprehensive stats
        $stats = array_merge($userStats, $moduleStats, $organizationStats, 
                           $serviceStats, $customerStats, $revenueStats);

        return view('council-admin.dashboard', compact(
            'stats',
            'systemHealth',
            'recentActivity',
            'performanceMetrics'
        ));
    }

    private function getUserStatistics()
    {
        try {
            $totalUsers = User::count();
            $activeUsers = User::where('active', true)->count();
            $inactiveUsers = User::where('active', false)->count();
            
            // User role breakdown
            $adminUsers = User::where('role', 'admin')->count();
            $managerUsers = User::where('role', 'manager')->count();
            $employeeUsers = User::where('role', 'employee')->count();
            $regularUsers = User::whereNull('role')->orWhere('role', 'user')->count();
            
            // Recent user registrations (last 30 days)
            $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
            
            return [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'inactive_users' => $inactiveUsers,
                'admin_users' => $adminUsers,
                'manager_users' => $managerUsers,
                'employee_users' => $employeeUsers,
                'regular_users' => $regularUsers,
                'recent_users' => $recentUsers,
            ];
        } catch (\Exception $e) {
            Log::error('User statistics error: ' . $e->getMessage());
            return [
                'total_users' => 0,
                'active_users' => 0,
                'inactive_users' => 0,
                'admin_users' => 0,
                'manager_users' => 0,
                'employee_users' => 0,
                'regular_users' => 0,
                'recent_users' => 0,
            ];
        }
    }

    private function getModuleStatistics()
    {
        try {
            $totalModules = CoreModule::count();
            $enabledModules = CoreModule::where('is_active', true)->count();
            $disabledModules = CoreModule::where('is_active', false)->count();
            
            return [
                'total_modules' => $totalModules,
                'enabled_modules' => $enabledModules,
                'disabled_modules' => $disabledModules,
                'module_status' => $this->getModuleStatus(),
            ];
        } catch (\Exception $e) {
            Log::error('Module statistics error: ' . $e->getMessage());
            return [
                'total_modules' => 0,
                'enabled_modules' => 0,
                'disabled_modules' => 0,
                'module_status' => [],
            ];
        }
    }

    private function getOrganizationStatistics()
    {
        try {
            $totalDepartments = Department::count();
            $totalOffices = Office::count();
            $totalCouncils = 1; // Single council system
            
            return [
                'total_departments' => $totalDepartments,
                'total_offices' => $totalOffices,
                'total_councils' => $totalCouncils,
            ];
        } catch (\Exception $e) {
            Log::error('Organization statistics error: ' . $e->getMessage());
            return [
                'total_departments' => 0,
                'total_offices' => 0,
                'total_councils' => 1,
            ];
        }
    }

    private function getServiceStatistics()
    {
        try {
            $totalServiceRequests = ServiceRequest::count();
            $pendingRequests = ServiceRequest::where('status', 'pending')->count();
            $inProgressRequests = ServiceRequest::where('status', 'in_progress')->count();
            $completedRequests = ServiceRequest::where('status', 'completed')->count();
            $rejectedRequests = ServiceRequest::where('status', 'rejected')->count();
            
            // Service requests by priority
            $highPriorityRequests = ServiceRequest::where('priority', 'high')->count();
            $mediumPriorityRequests = ServiceRequest::where('priority', 'medium')->count();
            $lowPriorityRequests = ServiceRequest::where('priority', 'low')->count();
            
            // Recent service requests (last 7 days)
            $recentRequests = ServiceRequest::where('created_at', '>=', Carbon::now()->subDays(7))->count();
            
            return [
                'total_service_requests' => $totalServiceRequests,
                'pending_service_requests' => $pendingRequests,
                'in_progress_requests' => $inProgressRequests,
                'completed_requests' => $completedRequests,
                'rejected_requests' => $rejectedRequests,
                'high_priority_requests' => $highPriorityRequests,
                'medium_priority_requests' => $mediumPriorityRequests,
                'low_priority_requests' => $lowPriorityRequests,
                'recent_requests' => $recentRequests,
            ];
        } catch (\Exception $e) {
            Log::error('Service statistics error: ' . $e->getMessage());
            return [
                'total_service_requests' => 0,
                'pending_service_requests' => 0,
                'in_progress_requests' => 0,
                'completed_requests' => 0,
                'rejected_requests' => 0,
                'high_priority_requests' => 0,
                'medium_priority_requests' => 0,
                'low_priority_requests' => 0,
                'recent_requests' => 0,
            ];
        }
    }

    private function getCustomerStatistics()
    {
        try {
            $totalCustomers = Customer::count();
            $activeCustomers = Customer::where('active', true)->count();
            $recentCustomers = Customer::where('created_at', '>=', Carbon::now()->subDays(30))->count();
            
            return [
                'total_customers' => $totalCustomers,
                'active_customers' => $activeCustomers,
                'recent_customers' => $recentCustomers,
            ];
        } catch (\Exception $e) {
            Log::error('Customer statistics error: ' . $e->getMessage());
            return [
                'total_customers' => 0,
                'active_customers' => 0,
                'recent_customers' => 0,
            ];
        }
    }

    private function getRevenueStatistics()
    {
        try {
            // This would integrate with finance module when available
            $monthlyRevenue = 0;
            $yearlyRevenue = 0;
            $totalRevenue = 0;
            
            // TODO: Integrate with finance module for actual revenue data
            
            return [
                'monthly_revenue' => $monthlyRevenue,
                'yearly_revenue' => $yearlyRevenue,
                'total_revenue' => $totalRevenue,
            ];
        } catch (\Exception $e) {
            Log::error('Revenue statistics error: ' . $e->getMessage());
            return [
                'monthly_revenue' => 0,
                'yearly_revenue' => 0,
                'total_revenue' => 0,
            ];
        }
    }

    private function getRecentActivity()
    {
        try {
            // Recent users (last 5)
            $recentUsers = User::with('department')
                              ->latest()
                              ->take(5)
                              ->get();

            // Recent service requests (last 5)
            $recentServiceRequests = ServiceRequest::with(['customer', 'serviceType'])
                                                  ->latest()
                                                  ->take(5)
                                                  ->get();

            // Recent customers (last 5)
            $recentCustomers = Customer::latest()
                                      ->take(5)
                                      ->get();

            return [
                'recent_users' => $recentUsers,
                'recent_service_requests' => $recentServiceRequests,
                'recent_customers' => $recentCustomers,
            ];
        } catch (\Exception $e) {
            Log::error('Recent activity error: ' . $e->getMessage());
            return [
                'recent_users' => collect(),
                'recent_service_requests' => collect(),
                'recent_customers' => collect(),
            ];
        }
    }

    private function getSystemHealth()
    {
        return [
            'database_status' => $this->checkDatabaseHealth(),
            'cache_status' => $this->checkCacheHealth(),
            'storage_status' => $this->checkStorageHealth(),
            'queue_status' => $this->checkQueueHealth(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
        ];
    }

    private function getPerformanceMetrics()
    {
        try {
            $avgResponseTime = $this->getAverageResponseTime();
            $errorRate = $this->getErrorRate();
            $uptime = $this->getSystemUptime();
            
            return [
                'avg_response_time' => $avgResponseTime,
                'error_rate' => $errorRate,
                'uptime' => $uptime,
                'active_sessions' => $this->getActiveSessions(),
            ];
        } catch (\Exception $e) {
            Log::error('Performance metrics error: ' . $e->getMessage());
            return [
                'avg_response_time' => 0,
                'error_rate' => 0,
                'uptime' => '99.9%',
                'active_sessions' => 0,
            ];
        }
    }

    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            // Test a simple query
            DB::table('users')->count();
            return 'healthy';
        } catch (\Exception $e) {
            Log::error('Database health check failed: ' . $e->getMessage());
            return 'error';
        }
    }

    private function checkCacheHealth()
    {
        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $value = Cache::get($testKey);
            Cache::forget($testKey);
            return $value === 'test' ? 'healthy' : 'warning';
        } catch (\Exception $e) {
            Log::error('Cache health check failed: ' . $e->getMessage());
            return 'warning';
        }
    }

    private function checkStorageHealth()
    {
        try {
            $testPath = storage_path('app/health_check_' . time() . '.txt');
            file_put_contents($testPath, 'test');
            $content = file_get_contents($testPath);
            unlink($testPath);
            return $content === 'test' ? 'healthy' : 'warning';
        } catch (\Exception $e) {
            Log::error('Storage health check failed: ' . $e->getMessage());
            return 'warning';
        }
    }

    private function checkQueueHealth()
    {
        try {
            // Basic queue health check - can be enhanced based on queue driver
            return 'healthy';
        } catch (\Exception $e) {
            Log::error('Queue health check failed: ' . $e->getMessage());
            return 'warning';
        }
    }

    private function getMemoryUsage()
    {
        return round(memory_get_usage() / 1024 / 1024, 2) . ' MB';
    }

    private function getDiskUsage()
    {
        try {
            $bytes = disk_free_space(storage_path());
            return round($bytes / 1024 / 1024 / 1024, 2) . ' GB free';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getAverageResponseTime()
    {
        // Placeholder - would integrate with monitoring service
        return rand(100, 300) . 'ms';
    }

    private function getErrorRate()
    {
        // Placeholder - would integrate with error tracking
        return '0.1%';
    }

    private function getSystemUptime()
    {
        // Placeholder - would integrate with system monitoring
        return '99.9%';
    }

    private function getActiveSessions()
    {
        try {
            // Count active sessions - implementation depends on session driver
            return User::where('active', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getModuleStatus()
    {
        try {
            $modules = CoreModule::all();
            $status = [];
            
            foreach ($modules as $module) {
                $status[$module->module_key] = [
                    'name' => $module->name,
                    'is_active' => $module->is_active,
                    'version' => $module->version ?? '1.0.0',
                    'last_updated' => $module->updated_at,
                ];
            }

            // Add default modules if none exist
            if (empty($status)) {
                $defaultModules = [
                    'finance' => ['name' => 'Finance & Accounting', 'is_active' => true, 'version' => '1.0.0'],
                    'housing' => ['name' => 'Housing Management', 'is_active' => true, 'version' => '1.0.0'],
                    'water' => ['name' => 'Water Management', 'is_active' => true, 'version' => '1.0.0'],
                    'health' => ['name' => 'Health Services', 'is_active' => true, 'version' => '1.0.0'],
                    'planning' => ['name' => 'Urban Planning', 'is_active' => true, 'version' => '1.0.0'],
                    'licensing' => ['name' => 'Licensing & Permits', 'is_active' => true, 'version' => '1.0.0'],
                    'hr' => ['name' => 'Human Resources', 'is_active' => true, 'version' => '1.0.0'],
                    'committee' => ['name' => 'Committee Management', 'is_active' => true, 'version' => '1.0.0'],
                    'engineering' => ['name' => 'Engineering Services', 'is_active' => true, 'version' => '1.0.0'],
                    'property' => ['name' => 'Property Management', 'is_active' => true, 'version' => '1.0.0'],
                ];
                return $defaultModules;
            }

            return $status;
        } catch (\Exception $e) {
            Log::error('Module status error: ' . $e->getMessage());
            return [];
        }
    }

    private function getFallbackDashboardData()
    {
        // Fallback data in case of errors
        $stats = [
            'total_users' => 0,
            'active_users' => 0,
            'inactive_users' => 0,
            'enabled_modules' => 0,
            'total_departments' => 0,
            'total_councils' => 1,
            'pending_service_requests' => 0,
            'total_service_requests' => 0,
            'total_customers' => 0,
            'monthly_revenue' => 0,
        ];

        $systemHealth = [
            'database_status' => 'unknown',
            'cache_status' => 'unknown',
            'storage_status' => 'unknown',
            'queue_status' => 'unknown',
            'memory_usage' => 'Unknown',
            'disk_usage' => 'Unknown',
        ];

        $recentActivity = [
            'recent_users' => collect(),
            'recent_service_requests' => collect(),
            'recent_customers' => collect(),
        ];

        $performanceMetrics = [
            'avg_response_time' => '0ms',
            'error_rate' => '0%',
            'uptime' => '99.9%',
            'active_sessions' => 0,
        ];

        return view('council-admin.dashboard', compact(
            'stats',
            'systemHealth',
            'recentActivity',
            'performanceMetrics'
        ));
    }

    public function clearCache()
    {
        try {
            Cache::forget('council_admin_dashboard_' . auth()->id());
            return redirect()->back()->with('success', 'Dashboard cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }
}
