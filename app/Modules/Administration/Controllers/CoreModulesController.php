<?php

namespace App\Modules\Administration\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CoreModulesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of all modules
     */
    public function index()
    {
        $modules = collect([
            (object)[
                'id' => 'finance',
                'name' => 'Finance & Accounting',
                'display_name' => 'Finance & Accounting',
                'description' => 'Complete financial management system with IPSAS compliance',
                'icon' => 'chart-line',
                'is_active' => true,
                'is_core' => true,
                'version' => '1.0.0',
                'getIcon' => function() { return 'fas fa-chart-line'; },
                'isCore' => function() { return true; },
                'isEnabled' => function() { return true; },
                'features' => $this->getModuleFeatures('finance')
            ],
            (object)[
                'id' => 'administration',
                'name' => 'Administration',
                'display_name' => 'Administration',
                'description' => 'User management, departments, and system administration',
                'icon' => 'users-cog',
                'is_active' => true,
                'is_core' => true,
                'version' => '1.0.0',
                'getIcon' => function() { return 'fas fa-users-cog'; },
                'isCore' => function() { return true; },
                'isEnabled' => function() { return true; },
                'features' => $this->getModuleFeatures('administration')
            ],
            (object)[
                'id' => 'housing',
                'name' => 'Housing Management',
                'display_name' => 'Housing Management',
                'description' => 'Comprehensive housing allocation and management system',
                'icon' => 'home',
                'is_active' => true,
                'is_core' => false,
                'version' => '1.0.0',
                'getIcon' => function() { return 'fas fa-home'; },
                'isCore' => function() { return false; },
                'isEnabled' => function() { return true; },
                'features' => [
                    (object)['id' => 'property_mgmt', 'name' => 'Property Management', 'is_active' => true],
                    (object)['id' => 'application_proc', 'name' => 'Application Processing', 'is_active' => true],
                    (object)['id' => 'waiting_list', 'name' => 'Waiting List Management', 'is_active' => true],
                    (object)['id' => 'allocation_sys', 'name' => 'Allocation System', 'is_active' => true],
                    (object)['id' => 'tenant_mgmt', 'name' => 'Tenant Management', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'billing',
                'name' => 'Municipal Billing',
                'display_name' => 'Municipal Billing',
                'description' => 'Automated billing system for municipal services',
                'icon' => 'receipt',
                'is_active' => true,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'service_billing', 'name' => 'Service Billing', 'is_active' => true],
                    (object)['id' => 'payment_proc', 'name' => 'Payment Processing', 'is_active' => true],
                    (object)['id' => 'debt_mgmt', 'name' => 'Debt Management', 'is_active' => true],
                    (object)['id' => 'revenue_reports', 'name' => 'Revenue Reports', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'utilities',
                'name' => 'Utilities Management',
                'display_name' => 'Utilities Management',
                'description' => 'Water, electricity and other utility services management',
                'icon' => 'plug',
                'is_active' => true,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'water_mgmt', 'name' => 'Water Management', 'is_active' => true],
                    (object)['id' => 'electricity_billing', 'name' => 'Electricity Billing', 'is_active' => true],
                    (object)['id' => 'meter_readings', 'name' => 'Meter Readings', 'is_active' => true],
                    (object)['id' => 'service_connections', 'name' => 'Service Connections', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'health',
                'name' => 'Health Services',
                'display_name' => 'Health Services',
                'description' => 'Health permits, inspections, and facility management',
                'icon' => 'heartbeat',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'health_permits', 'name' => 'Health Permits', 'is_active' => true],
                    (object)['id' => 'inspections', 'name' => 'Inspections', 'is_active' => true],
                    (object)['id' => 'facility_management', 'name' => 'Facility Management', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'licensing',
                'name' => 'Business Licensing',
                'display_name' => 'Business Licensing',
                'description' => 'Business license application and management system',
                'icon' => 'certificate',
                'is_active' => true,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'license_apps', 'name' => 'License Applications', 'is_active' => true],
                    (object)['id' => 'renewal_mgmt', 'name' => 'Renewal Management', 'is_active' => true],
                    (object)['id' => 'compliance_track', 'name' => 'Compliance Tracking', 'is_active' => true],
                    (object)['id' => 'fee_collection', 'name' => 'Fee Collection', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'engineering',
                'name' => 'Engineering Services',
                'display_name' => 'Engineering Services',
                'description' => 'Infrastructure, projects, and facility management',
                'icon' => 'tools',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'infrastructure_mgmt', 'name' => 'Infrastructure Management', 'is_active' => true],
                    (object)['id' => 'project_management', 'name' => 'Project Management', 'is_active' => true],
                    (object)['id' => 'facility_maintenance', 'name' => 'Facility Maintenance', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'committee',
                'name' => 'Committee Management',
                'display_name' => 'Committee Management',
                'description' => 'Committee meetings, agendas, and resolutions',
                'icon' => 'users',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'meeting_scheduling', 'name' => 'Meeting Scheduling', 'is_active' => true],
                    (object)['id' => 'agenda_management', 'name' => 'Agenda Management', 'is_active' => true],
                    (object)['id' => 'resolution_tracking', 'name' => 'Resolution Tracking', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'hr',
                'name' => 'Human Resources',
                'display_name' => 'Human Resources',
                'description' => 'Employee management, payroll, and attendance',
                'icon' => 'user-tie',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'employee_management', 'name' => 'Employee Management', 'is_active' => true],
                    (object)['id' => 'payroll_processing', 'name' => 'Payroll Processing', 'is_active' => true],
                    (object)['id' => 'attendance_tracking', 'name' => 'Attendance Tracking', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'cemeteries',
                'name' => 'Cemetery Management',
                'display_name' => 'Cemetery Management',
                'description' => 'Plot allocation, burial records, and maintenance',
                'icon' => 'cross',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'plot_allocation', 'name' => 'Plot Allocation', 'is_active' => true],
                    (object)['id' => 'burial_records', 'name' => 'Burial Records', 'is_active' => true],
                    (object)['id' => 'cemetery_maintenance', 'name' => 'Cemetery Maintenance', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'parking',
                'name' => 'Parking Management',
                'display_name' => 'Parking Management',
                'description' => 'Parking zones, permits, and violations',
                'icon' => 'parking',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'parking_zones', 'name' => 'Parking Zones', 'is_active' => true],
                    (object)['id' => 'permit_management', 'name' => 'Permit Management', 'is_active' => true],
                    (object)['id' => 'violation_tracking', 'name' => 'Violation Tracking', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'markets',
                'name' => 'Market Management',
                'display_name' => 'Market Management',
                'description' => 'Market stalls, vendor management, and revenue collection',
                'icon' => 'store',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'stall_allocation', 'name' => 'Stall Allocation', 'is_active' => true],
                    (object)['id' => 'vendor_management', 'name' => 'Vendor Management', 'is_active' => true],
                    (object)['id' => 'revenue_collection', 'name' => 'Revenue Collection', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'survey',
                'name' => 'Survey Services',
                'display_name' => 'Survey Services',
                'description' => 'Land surveying, measurements, and boundary management',
                'icon' => 'map',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'land_surveying', 'name' => 'Land Surveying', 'is_active' => true],
                    (object)['id' => 'measurement_records', 'name' => 'Measurement Records', 'is_active' => true],
                    (object)['id' => 'boundary_management', 'name' => 'Boundary Management', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'property',
                'name' => 'Property Management',
                'display_name' => 'Property Management',
                'description' => 'Property records, valuations, and lease management',
                'icon' => 'building',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'property_records', 'name' => 'Property Records', 'is_active' => true],
                    (object)['id' => 'valuation_management', 'name' => 'Valuation Management', 'is_active' => true],
                    (object)['id' => 'lease_management', 'name' => 'Lease Management', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'planning',
                'name' => 'Planning & Development',
                'display_name' => 'Planning & Development',
                'description' => 'Urban planning and development control system',
                'icon' => 'drafting-compass',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'planning_apps', 'name' => 'Planning Applications', 'is_active' => true],
                    (object)['id' => 'zoning_mgmt', 'name' => 'Zoning Management', 'is_active' => true],
                    (object)['id' => 'dev_control', 'name' => 'Development Control', 'is_active' => true],
                    (object)['id' => 'building_plans', 'name' => 'Building Plans Approval', 'is_active' => true]
                ]
            ],
            (object)[
                'id' => 'inventory',
                'name' => 'Inventory Management',
                'display_name' => 'Inventory Management',
                'description' => 'Stock control, procurement, and asset tracking',
                'icon' => 'boxes',
                'is_active' => false,
                'is_core' => false,
                'version' => '1.0.0',
                'features' => [
                    (object)['id' => 'stock_control', 'name' => 'Stock Control', 'is_active' => true],
                    (object)['id' => 'procurement_management', 'name' => 'Procurement Management', 'is_active' => true],
                    (object)['id' => 'asset_tracking', 'name' => 'Asset Tracking', 'is_active' => true]
                ]
            ]
        ]);

        return view('administration.core-modules.index', compact('modules'));
    }

    /**
     * Get all available modules
     */
    private function getAllModules()
    {
        return [
            'administration' => [
                'name' => 'Administration',
                'description' => 'User management, departments, and system administration',
                'icon' => 'fas fa-users-cog',
                'core' => true
            ],
            'finance' => [
                'name' => 'Finance & Accounting',
                'description' => 'Financial management, budgets, and accounting',
                'icon' => 'fas fa-dollar-sign',
                'core' => true
            ],
            'housing' => [
                'name' => 'Housing Management',
                'description' => 'Housing allocations, waiting lists, and property management',
                'icon' => 'fas fa-home',
                'core' => false
            ],
            'water' => [
                'name' => 'Water Management',
                'description' => 'Water connections, billing, and quality management',
                'icon' => 'fas fa-tint',
                'core' => false
            ],
            'health' => [
                'name' => 'Health Services',
                'description' => 'Health permits, inspections, and facility management',
                'icon' => 'fas fa-heartbeat',
                'core' => false
            ],
            'licensing' => [
                'name' => 'Licensing & Permits',
                'description' => 'Business licenses, permits, and regulatory compliance',
                'icon' => 'fas fa-certificate',
                'core' => false
            ],
            'engineering' => [
                'name' => 'Engineering Services',
                'description' => 'Infrastructure, projects, and facility management',
                'icon' => 'fas fa-tools',
                'core' => false
            ],
            'committee' => [
                'name' => 'Committee Management',
                'description' => 'Committee meetings, agendas, and resolutions',
                'icon' => 'fas fa-users',
                'core' => false
            ],
            'hr' => [
                'name' => 'Human Resources',
                'description' => 'Employee management, payroll, and attendance',
                'icon' => 'fas fa-user-tie',
                'core' => false
            ],
            'cemeteries' => [
                'name' => 'Cemetery Management',
                'description' => 'Plot allocation, burial records, and maintenance',
                'icon' => 'fas fa-cross',
                'core' => false
            ],
            'parking' => [
                'name' => 'Parking Management',
                'description' => 'Parking zones, permits, and violations',
                'icon' => 'fas fa-parking',
                'core' => false
            ],
            'markets' => [
                'name' => 'Market Management',
                'description' => 'Market stalls, vendor management, and revenue collection',
                'icon' => 'fas fa-store',
                'core' => false
            ],
            'survey' => [
                'name' => 'Survey Services',
                'description' => 'Land surveying, measurements, and boundary management',
                'icon' => 'fas fa-map',
                'core' => false
            ],
            'utilities' => [
                'name' => 'Utilities Management',
                'description' => 'Electricity, gas, and waste collection services',
                'icon' => 'fas fa-bolt',
                'core' => false
            ],
            'property' => [
                'name' => 'Property Management',
                'description' => 'Property records, valuations, and lease management',
                'icon' => 'fas fa-building',
                'core' => false
            ],
            'planning' => [
                'name' => 'Town Planning',
                'description' => 'Planning applications, zoning, and development control',
                'icon' => 'fas fa-map-marked-alt',
                'core' => false
            ],
            'inventory' => [
                'name' => 'Inventory Management',
                'description' => 'Stock control, procurement, and asset tracking',
                'icon' => 'fas fa-boxes',
                'core' => false
            ]
        ];
    }

    /**
     * Get module status from cache or default settings
     */
    private function getModuleStatuses()
    {
        $defaultStatuses = [
            'administration' => true,
            'finance' => true,
            'housing' => true,
            'water' => true,
            'health' => true,
            'licensing' => true,
            'engineering' => true,
            'committee' => true,
            'hr' => true,
            'cemeteries' => true,
            'parking' => true,
            'markets' => true,
            'survey' => true,
            'utilities' => true,
            'property' => true,
            'planning' => true,
            'inventory' => true
        ];

        return Cache::get('module_statuses', $defaultStatuses);
    }

    /**
     * Toggle module status
     */
    public function toggle(Request $request, $moduleId)
    {
        $user = auth()->user();

        // Only admin can toggle modules
        if (!in_array($user->role, ['admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = \App\Models\CoreModule::find($moduleId);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        // Core modules cannot be disabled
        if ($module->isCore() && $request->boolean('status') === false) {
            return response()->json(['error' => 'Core modules cannot be disabled'], 400);
        }

        $module->is_active = $request->boolean('status');
        $module->save();

        Log::info('Module status changed', [
            'module' => $module->name,
            'status' => $module->is_active,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => $module->name . ' has been ' . ($module->is_active ? 'enabled' : 'disabled'),
            'module' => $module->name,
            'status' => $module->is_active
        ]);
    }

    /**
     * Check if a module is enabled
     */
    public static function isModuleEnabled($moduleName)
    {
        $module = \App\Models\CoreModule::where('name', $moduleName)->first();
        return $module ? $module->isEnabled() : false;
    }

    /**
     * Get enabled modules
     */
    public static function getEnabledModules()
    {
        return \App\Models\CoreModule::where('is_active', true)->pluck('name')->toArray();
    }

    /**
     * Get module features with their current status
     */
    private function getModuleFeatures($moduleKey)
    {
        $defaultFeatures = [
            'finance' => [
                ['id' => 'chart_accounts', 'name' => 'Chart of Accounts'],
                ['id' => 'general_ledger', 'name' => 'General Ledger'],
                ['id' => 'accounts_receivable', 'name' => 'Accounts Receivable'],
                ['id' => 'accounts_payable', 'name' => 'Accounts Payable'],
                ['id' => 'fixed_assets', 'name' => 'Fixed Assets'],
                ['id' => 'ipsas_reporting', 'name' => 'IPSAS Reporting']
            ],
            'administration' => [
                ['id' => 'user_management', 'name' => 'User Management'],
                ['id' => 'department_management', 'name' => 'Department Management'],
                ['id' => 'role_management', 'name' => 'Role Management'],
                ['id' => 'permission_management', 'name' => 'Permission Management'],
                ['id' => 'audit_log', 'name' => 'Audit Log']
            ],
            'housing' => [
                ['id' => 'property_mgmt', 'name' => 'Property Management'],
                ['id' => 'application_proc', 'name' => 'Application Processing'],
                ['id' => 'waiting_list', 'name' => 'Waiting List Management'],
                ['id' => 'allocation_sys', 'name' => 'Allocation System'],
                ['id' => 'tenant_mgmt', 'name' => 'Tenant Management']
            ],
            'billing' => [
                ['id' => 'service_billing', 'name' => 'Service Billing'],
                ['id' => 'payment_proc', 'name' => 'Payment Processing'],
                ['id' => 'debt_mgmt', 'name' => 'Debt Management'],
                ['id' => 'revenue_reports', 'name' => 'Revenue Reports']
            ],
            'utilities' => [
                ['id' => 'water_mgmt', 'name' => 'Water Management'],
                ['id' => 'electricity_billing', 'name' => 'Electricity Billing'],
                ['id' => 'meter_readings', 'name' => 'Meter Readings'],
                ['id' => 'service_connections', 'name' => 'Service Connections']
            ],
            'health' => [
                ['id' => 'health_permits', 'name' => 'Health Permits'],
                ['id' => 'inspections', 'name' => 'Inspections'],
                ['id' => 'facility_management', 'name' => 'Facility Management']
            ],
            'licensing' => [
                ['id' => 'license_apps', 'name' => 'License Applications'],
                ['id' => 'renewal_mgmt', 'name' => 'Renewal Management'],
                ['id' => 'compliance_track', 'name' => 'Compliance Tracking'],
                ['id' => 'fee_collection', 'name' => 'Fee Collection']
            ],
            'engineering' => [
                ['id' => 'infrastructure_mgmt', 'name' => 'Infrastructure Management'],
                ['id' => 'project_management', 'name' => 'Project Management'],
                ['id' => 'facility_maintenance', 'name' => 'Facility Maintenance']
            ],
            'committee' => [
                ['id' => 'meeting_scheduling', 'name' => 'Meeting Scheduling'],
                ['id' => 'agenda_management', 'name' => 'Agenda Management'],
                ['id' => 'resolution_tracking', 'name' => 'Resolution Tracking']
            ],
            'hr' => [
                ['id' => 'employee_management', 'name' => 'Employee Management'],
                ['id' => 'payroll_processing', 'name' => 'Payroll Processing'],
                ['id' => 'attendance_tracking', 'name' => 'Attendance Tracking']
            ]
        ];

        $features = $defaultFeatures[$moduleKey] ?? [];
        $result = [];

        foreach ($features as $feature) {
            $dbFeature = \App\Models\ModuleFeature::where('feature_key', $feature['id'])
                ->where('module_name', $this->getModuleNameById($moduleKey))
                ->first();

            $result[] = (object)[
                'id' => $feature['id'],
                'name' => $feature['name'],
                'is_active' => $dbFeature ? $dbFeature->is_enabled : true
            ];
        }

        return $result;
    }

    /**
     * Toggle feature status
     */
    public function toggleFeature(Request $request, $moduleId, $featureId)
    {
        $user = auth()->user();

        // Only admin can toggle features
        if (!in_array($user->role, ['admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Find or create the module feature record
        $feature = \App\Models\ModuleFeature::where('core_module_id', $moduleId)
            ->where('feature_key', $featureId)
            ->first();

        if (!$feature) {
            // Create new feature record if it doesn't exist
            $feature = \App\Models\ModuleFeature::create([
                'core_module_id' => $moduleId,
                'module_name' => $this->getModuleNameById($moduleId),
                'feature_name' => ucfirst(str_replace('_', ' ', $featureId)),
                'feature_key' => $featureId,
                'is_enabled' => $request->boolean('status'),
                'sort_order' => 0
            ]);
        } else {
            $feature->is_enabled = $request->boolean('status');
            $feature->save();
        }

        Log::info('Feature status changed', [
            'module_id' => $moduleId,
            'feature' => $feature->feature_name,
            'status' => $feature->is_enabled,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => $feature->feature_name . ' has been ' . ($feature->is_enabled ? 'enabled' : 'disabled'),
            'feature' => $feature->feature_name,
            'status' => $feature->is_enabled
        ]);
    }

    /**
     * Get module name by ID
     */
    private function getModuleNameById($moduleId)
    {
        $modules = [
            'finance' => 'Finance & Accounting',
            'administration' => 'Administration',
            'housing' => 'Housing Management',
            'billing' => 'Municipal Billing',
            'utilities' => 'Utilities Management',
            'health' => 'Health Services',
            'licensing' => 'Business Licensing',
            'engineering' => 'Engineering Services',
            'committee' => 'Committee Management',
            'hr' => 'Human Resources',
            'cemeteries' => 'Cemetery Management',
            'parking' => 'Parking Management',
            'markets' => 'Market Management',
            'survey' => 'Survey Services',
            'property' => 'Property Management',
            'planning' => 'Planning & Development',
            'inventory' => 'Inventory Management'
        ];

        return $modules[$moduleId] ?? 'Unknown Module';
    }
}