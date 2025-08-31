<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoreModule;
use App\Models\ModuleFeature;

class CoreModulesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'administration',
                'display_name' => 'Administration CRM',
                'description' => 'Customer relationship management and service delivery',
                'icon' => 'users-cog',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 1,
                'features' => [
                    ['feature_name' => 'Customer Management', 'feature_key' => 'admin_customers', 'description' => 'Manage customer records and profiles'],
                    ['feature_name' => 'Service Requests', 'feature_key' => 'admin_service_requests', 'description' => 'Handle customer service requests'],
                    ['feature_name' => 'Communications', 'feature_key' => 'admin_communications', 'description' => 'Customer communications and notifications'],
                    ['feature_name' => 'Departments', 'feature_key' => 'admin_departments', 'description' => 'Manage municipal departments'],
                ]
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance Management',
                'description' => 'Financial management with IPSAS compliance',
                'icon' => 'dollar-sign',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 2,
                'features' => [
                    ['feature_name' => 'General Ledger', 'feature_key' => 'finance_gl', 'description' => 'General ledger management'],
                    ['feature_name' => 'Accounts Receivable', 'feature_key' => 'finance_ar', 'description' => 'Manage customer invoices and payments'],
                    ['feature_name' => 'Accounts Payable', 'feature_key' => 'finance_ap', 'description' => 'Manage supplier bills and payments'],
                    ['feature_name' => 'Budget Management', 'feature_key' => 'finance_budget', 'description' => 'Budget planning and monitoring'],
                    ['feature_name' => 'Fixed Assets', 'feature_key' => 'finance_assets', 'description' => 'Fixed asset register and depreciation'],
                ]
            ],
            [
                'name' => 'housing',
                'display_name' => 'Housing Management',
                'description' => 'Manage waiting lists, allocations, and housing records',
                'icon' => 'home',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 3,
                'features' => [
                    ['feature_name' => 'Waiting List', 'feature_key' => 'housing_waiting_list', 'description' => 'Housing waiting list management'],
                    ['feature_name' => 'Allocations', 'feature_key' => 'housing_allocations', 'description' => 'Housing allocation management'],
                    ['feature_name' => 'Properties', 'feature_key' => 'housing_properties', 'description' => 'Property management'],
                    ['feature_name' => 'Tenants', 'feature_key' => 'housing_tenants', 'description' => 'Tenant management'],
                ]
            ],
            [
                'name' => 'water',
                'display_name' => 'Water Management',
                'description' => 'Water billing, connections, and quality management',
                'icon' => 'tint',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 4,
                'features' => [
                    ['feature_name' => 'Connections', 'feature_key' => 'water_connections', 'description' => 'Water connection management'],
                    ['feature_name' => 'Billing', 'feature_key' => 'water_billing', 'description' => 'Water billing and invoicing'],
                    ['feature_name' => 'Meter Reading', 'feature_key' => 'water_meters', 'description' => 'Meter reading management'],
                    ['feature_name' => 'Quality Testing', 'feature_key' => 'water_quality', 'description' => 'Water quality testing'],
                ]
            ],
            [
                'name' => 'health',
                'display_name' => 'Health Services',
                'description' => 'Health inspections, permits, and environmental health',
                'icon' => 'medkit',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 5,
                'features' => [
                    ['feature_name' => 'Health Inspections', 'feature_key' => 'health_inspections', 'description' => 'Conduct health and safety inspections'],
                    ['feature_name' => 'Health Permits', 'feature_key' => 'health_permits', 'description' => 'Issue health-related permits'],
                    ['feature_name' => 'Environmental Health', 'feature_key' => 'health_environmental', 'description' => 'Environmental health monitoring'],
                ]
            ],
            [
                'name' => 'planning',
                'display_name' => 'Town Planning',
                'description' => 'Development applications and planning approvals',
                'icon' => 'building',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 6,
                'features' => [
                    ['feature_name' => 'Development Applications', 'feature_key' => 'planning_applications', 'description' => 'Manage development applications'],
                    ['feature_name' => 'Zoning Management', 'feature_key' => 'planning_zoning', 'description' => 'Zoning and land use planning'],
                    ['feature_name' => 'Building Approvals', 'feature_key' => 'planning_approvals', 'description' => 'Building plan approvals'],
                ]
            ],
            [
                'name' => 'licensing',
                'display_name' => 'Licensing',
                'description' => 'Business licenses and permits management',
                'icon' => 'certificate',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 7,
                'features' => [
                    ['feature_name' => 'Business Licenses', 'feature_key' => 'licensing_business', 'description' => 'Business license management'],
                    ['feature_name' => 'Operating Permits', 'feature_key' => 'licensing_permits', 'description' => 'Operating permit management'],
                    ['feature_name' => 'License Renewals', 'feature_key' => 'licensing_renewals', 'description' => 'License renewal processing'],
                ]
            ],
            [
                'name' => 'hr',
                'display_name' => 'Human Resources',
                'description' => 'Employee management, payroll, and attendance',
                'icon' => 'user-tie',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 8,
                'features' => [
                    ['feature_name' => 'Employee Management', 'feature_key' => 'hr_employees', 'description' => 'Employee records and profiles'],
                    ['feature_name' => 'Payroll Processing', 'feature_key' => 'hr_payroll', 'description' => 'Payroll calculation and processing'],
                    ['feature_name' => 'Attendance Tracking', 'feature_key' => 'hr_attendance', 'description' => 'Employee attendance management'],
                    ['feature_name' => 'Leave Management', 'feature_key' => 'hr_leave', 'description' => 'Employee leave tracking'],
                ]
            ],
            [
                'name' => 'committee',
                'display_name' => 'Committee Management',
                'description' => 'Committee meetings, agendas, and minutes',
                'icon' => 'users',
                'is_active' => true,
                'is_core' => true,
                'sort_order' => 9,
                'features' => [
                    ['feature_name' => 'Meeting Management', 'feature_key' => 'committee_meetings', 'description' => 'Schedule and manage meetings'],
                    ['feature_name' => 'Agenda Creation', 'feature_key' => 'committee_agendas', 'description' => 'Create and manage meeting agendas'],
                    ['feature_name' => 'Minutes Recording', 'feature_key' => 'committee_minutes', 'description' => 'Record and manage meeting minutes'],
                    ['feature_name' => 'Member Management', 'feature_key' => 'committee_members', 'description' => 'Manage committee members'],
                ]
            ],
            [
                'name' => 'facilities',
                'display_name' => 'Facility Bookings',
                'description' => 'Manage bookings for pools, halls, and recreational facilities',
                'icon' => 'swimming-pool',
                'is_active' => true,
                'is_core' => false,
                'sort_order' => 10,
                'features' => [
                    ['feature_name' => 'Pool Bookings', 'feature_key' => 'facilities_pools', 'description' => 'Swimming pool bookings'],
                    ['feature_name' => 'Hall Rentals', 'feature_key' => 'facilities_halls', 'description' => 'Community hall rentals'],
                    ['feature_name' => 'Sports Facilities', 'feature_key' => 'facilities_sports', 'description' => 'Sports facility bookings'],
                    ['feature_name' => 'Gate Takings', 'feature_key' => 'facilities_gate_takings', 'description' => 'Daily gate revenue tracking'],
                ]
            ]
        ];

        foreach ($modules as $moduleData) {
            $features = $moduleData['features'];
            unset($moduleData['features']);

            $module = CoreModule::create(array_merge($moduleData, [
                'permissions' => [],
                'version' => '1.0.0'
            ]));

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
}