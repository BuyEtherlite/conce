<?php

namespace App\Http\Controllers\CouncilAdmin;

use App\Http\Controllers\Controller;
use App\Models\CoreModule;
use App\Models\ModuleFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = CoreModule::with('features')->orderBy('sort_order')->get();

        // If no modules exist, create default ones
        if ($modules->isEmpty()) {
            $this->createDefaultModules();
            $modules = CoreModule::with('features')->orderBy('sort_order')->get();
        }

        return view('council-admin.modules.index', compact('modules'));
    }

    public function toggle(Request $request, $id)
    {
        try {
            $module = CoreModule::findOrFail($id);
            $module->is_active = !$module->is_active;
            $module->save();

            Log::info("Module {$module->name} toggled to " . ($module->is_active ? 'active' : 'inactive'));

            return response()->json([
                'success' => true,
                'message' => "Module {$module->display_name} " . ($module->is_active ? 'enabled' : 'disabled') . ' successfully.',
                'is_active' => $module->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Module toggle error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle module: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view('council-admin.modules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:core_modules',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_core' => 'boolean',
            'permissions' => 'nullable|array',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            $module = CoreModule::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'icon' => $request->icon ?: 'cube',
                'is_active' => true,
                'is_core' => $request->boolean('is_core'),
                'permissions' => $request->permissions ?: [],
                'version' => '1.0.0',
                'sort_order' => $request->sort_order ?: 999
            ]);

            return redirect()->route('council-admin.modules.index')
                ->with('success', 'Module created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create module: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $module = CoreModule::with('features')->findOrFail($id);

        // Check if module has features and create default ones if needed
        if ($module->features->count() === 0) {
            $this->createDefaultFeatures($module);
            $module->load('features');
        }

        return view('council-admin.modules.show', compact('module'));
    }

    public function edit($id)
    {
        $module = CoreModule::findOrFail($id);
        return view('council-admin.modules.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        $module = CoreModule::findOrFail($id);

        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'permissions' => 'nullable|array',
            'sort_order' => 'nullable|integer'
        ]);

        try {
            $module->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
                'icon' => $request->icon ?: 'cube',
                'permissions' => $request->permissions ?: [],
                'sort_order' => $request->sort_order ?: $module->sort_order
            ]);

            return redirect()->route('council-admin.modules.index')
                ->with('success', 'Module updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update module: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(CoreModule $module)
    {
        $module->delete();

        return redirect()->route('council-admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }

    public function toggleFeature(Request $request, CoreModule $module, $feature)
    {
        $features = $module->features ?? [];

        if (isset($features[$feature])) {
            $features[$feature]['enabled'] = !$features[$feature]['enabled'];
            $module->features = $features;
            $module->save();

            return response()->json([
                'success' => true,
                'enabled' => $features[$feature]['enabled']
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    private function createDefaultModules()
    {
        $defaultModules = [
            [
                'name' => 'administration',
                'display_name' => 'Administration CRM',
                'description' => 'Customer relationship management and service delivery',
                'icon' => 'users-cog',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance Management',
                'description' => 'Financial management with IPSAS compliance',
                'icon' => 'dollar-sign',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'housing',
                'display_name' => 'Housing Management',
                'description' => 'Manage waiting lists, allocations, and housing records',
                'icon' => 'home',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'water',
                'display_name' => 'Water Management',
                'description' => 'Water billing, connections, and quality management',
                'icon' => 'tint',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'health',
                'display_name' => 'Health Services',
                'description' => 'Health inspections, permits, and environmental health',
                'icon' => 'medkit',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'planning',
                'display_name' => 'Town Planning',
                'description' => 'Development applications and planning approvals',
                'icon' => 'building',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'licensing',
                'display_name' => 'Licensing',
                'description' => 'Business licenses and permits management',
                'icon' => 'certificate',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'hr',
                'display_name' => 'Human Resources',
                'description' => 'Employee management, payroll, and attendance',
                'icon' => 'user-tie',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 8
            ],
            [
                'name' => 'committee',
                'display_name' => 'Committee Management',
                'description' => 'Committee meetings, agendas, and minutes',
                'icon' => 'users',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 9
            ],
            [
                'name' => 'facilities',
                'display_name' => 'Facility Bookings',
                'description' => 'Manage bookings for pools, halls, and recreational facilities',
                'icon' => 'swimming-pool',
                'is_active' => true,
                'is_core' => false,
                'sort_order' => 10
            ]
        ];

        foreach ($defaultModules as $moduleData) {
            $module = CoreModule::create(array_merge($moduleData, [
                'permissions' => [],
                'version' => '1.0.0'
            ]));

            $this->createDefaultFeatures($module);
        }
    }

    private function createDefaultFeatures($module)
    {
        $features = [];

        switch ($module->name) {
            case 'administration':
                $features = [
                    ['feature_name' => 'Customer Management', 'feature_key' => 'admin_customers', 'description' => 'Manage customer records and profiles'],
                    ['feature_name' => 'Service Requests', 'feature_key' => 'admin_service_requests', 'description' => 'Handle customer service requests'],
                    ['feature_name' => 'Communications', 'feature_key' => 'admin_communications', 'description' => 'Customer communications and notifications'],
                    ['feature_name' => 'Departments', 'feature_key' => 'admin_departments', 'description' => 'Manage municipal departments'],
                ];
                break;

            case 'finance':
                $features = [
                    ['feature_name' => 'General Ledger', 'feature_key' => 'finance_gl', 'description' => 'General ledger management'],
                    ['feature_name' => 'Accounts Receivable', 'feature_key' => 'finance_ar', 'description' => 'Manage customer invoices and payments'],
                    ['feature_name' => 'Accounts Payable', 'feature_key' => 'finance_ap', 'description' => 'Manage supplier bills and payments'],
                    ['feature_name' => 'Budget Management', 'feature_key' => 'finance_budget', 'description' => 'Budget planning and monitoring'],
                    ['feature_name' => 'Fixed Assets', 'feature_key' => 'finance_assets', 'description' => 'Fixed asset register and depreciation'],
                ];
                break;

            case 'housing':
                $features = [
                    ['feature_name' => 'Waiting List', 'feature_key' => 'housing_waiting_list', 'description' => 'Housing waiting list management'],
                    ['feature_name' => 'Allocations', 'feature_key' => 'housing_allocations', 'description' => 'Housing allocation management'],
                    ['feature_name' => 'Properties', 'feature_key' => 'housing_properties', 'description' => 'Property management'],
                    ['feature_name' => 'Tenants', 'feature_key' => 'housing_tenants', 'description' => 'Tenant management'],
                ];
                break;

            case 'water':
                $features = [
                    ['feature_name' => 'Connections', 'feature_key' => 'water_connections', 'description' => 'Water connection management'],
                    ['feature_name' => 'Billing', 'feature_key' => 'water_billing', 'description' => 'Water billing and invoicing'],
                    ['feature_name' => 'Meter Reading', 'feature_key' => 'water_meters', 'description' => 'Meter reading management'],
                    ['feature_name' => 'Quality Testing', 'feature_key' => 'water_quality', 'description' => 'Water quality testing'],
                ];
                break;

            case 'facilities':
                $features = [
                    ['feature_name' => 'Pool Bookings', 'feature_key' => 'facilities_pools', 'description' => 'Swimming pool bookings'],
                    ['feature_name' => 'Hall Rentals', 'feature_key' => 'facilities_halls', 'description' => 'Community hall rentals'],
                    ['feature_name' => 'Sports Facilities', 'feature_key' => 'facilities_sports', 'description' => 'Sports facility bookings'],
                    ['feature_name' => 'Gate Takings', 'feature_key' => 'facilities_gate_takings', 'description' => 'Daily gate revenue tracking'],
                ];
                break;

            default:
                $features = [
                    ['feature_name' => 'Basic Management', 'feature_key' => $module->name . '_basic', 'description' => 'Basic module functionality'],
                ];
        }

        foreach ($features as $index => $featureData) {
            ModuleFeature::create(array_merge($featureData, [
                'core_module_id' => $module->id,
                'module_name' => $module->name,
                'is_enabled' => true,
                'permissions' => [],
                'sort_order' => $index + 1
            ]));
        }
    }
}