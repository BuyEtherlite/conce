<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop table if it exists to ensure clean creation
        Schema::dropIfExists('module_features');
        
        Schema::create('module_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('core_module_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->json('permissions')->nullable();
            $table->json('configuration')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['core_module_id', 'name']);
            $table->index(['core_module_id', 'is_enabled']);
        });

        // Seed with default features for each module
        $this->seedModuleFeatures();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_features');
    }

    private function seedModuleFeatures(): void
    {
        $moduleFeatures = [
            'administration' => [
                ['name' => 'user_management', 'display_name' => 'User Management', 'description' => 'Manage system users and permissions'],
                ['name' => 'department_management', 'display_name' => 'Department Management', 'description' => 'Manage organizational departments'],
                ['name' => 'crm', 'display_name' => 'Customer Relationship Management', 'description' => 'Manage customer interactions and service requests'],
            ],
            'finance' => [
                ['name' => 'general_ledger', 'display_name' => 'General Ledger', 'description' => 'Core accounting functionality'],
                ['name' => 'billing', 'display_name' => 'Billing & Invoicing', 'description' => 'Generate and manage bills and invoices'],
                ['name' => 'receipts', 'display_name' => 'Receipt Management', 'description' => 'Process and track payments'],
                ['name' => 'accounting_integration', 'display_name' => 'Accounting Integration', 'description' => 'Integration with external accounting systems'],
                ['name' => 'budget_management', 'display_name' => 'Budget Management', 'description' => 'Create and monitor budgets'],
                ['name' => 'financial_reporting', 'display_name' => 'Financial Reporting', 'description' => 'Generate financial reports and statements'],
            ],
            'housing' => [
                ['name' => 'waiting_list', 'display_name' => 'Waiting List Management', 'description' => 'Manage housing application waiting lists'],
                ['name' => 'allocations', 'display_name' => 'Housing Allocations', 'description' => 'Allocate housing units to applicants'],
                ['name' => 'properties', 'display_name' => 'Property Management', 'description' => 'Manage housing properties and maintenance'],
                ['name' => 'tenancy_management', 'display_name' => 'Tenancy Management', 'description' => 'Manage tenant relationships and agreements'],
            ],
            'water' => [
                ['name' => 'connections', 'display_name' => 'Water Connections', 'description' => 'Manage water service connections'],
                ['name' => 'billing', 'display_name' => 'Water Billing', 'description' => 'Generate water consumption bills'],
                ['name' => 'quality_testing', 'display_name' => 'Water Quality Testing', 'description' => 'Track water quality test results'],
                ['name' => 'meter_management', 'display_name' => 'Meter Management', 'description' => 'Manage water meters and readings'],
            ],
            'engineering' => [
                ['name' => 'projects', 'display_name' => 'Project Management', 'description' => 'Manage engineering projects'],
                ['name' => 'inspections', 'display_name' => 'Building Inspections', 'description' => 'Conduct and track building inspections'],
                ['name' => 'work_orders', 'display_name' => 'Work Order Management', 'description' => 'Create and manage work orders'],
                ['name' => 'infrastructure', 'display_name' => 'Infrastructure Management', 'description' => 'Manage municipal infrastructure assets'],
            ],
            'hr' => [
                ['name' => 'employee_management', 'display_name' => 'Employee Management', 'description' => 'Manage employee records and information'],
                ['name' => 'payroll', 'display_name' => 'Payroll Processing', 'description' => 'Process employee payroll'],
                ['name' => 'attendance', 'display_name' => 'Attendance Tracking', 'description' => 'Track employee attendance and time'],
                ['name' => 'leave_management', 'display_name' => 'Leave Management', 'description' => 'Manage employee leave requests'],
            ],
            'health' => [
                ['name' => 'inspections', 'display_name' => 'Health Inspections', 'description' => 'Conduct health and safety inspections'],
                ['name' => 'permits', 'display_name' => 'Health Permits', 'description' => 'Issue and manage health permits'],
                ['name' => 'records', 'display_name' => 'Health Records', 'description' => 'Maintain health-related records and documentation'],
                ['name' => 'environmental_health', 'display_name' => 'Environmental Health', 'description' => 'Monitor environmental health conditions'],
            ],
            'licensing' => [
                ['name' => 'applications', 'display_name' => 'License Applications', 'description' => 'Process business license applications'],
                ['name' => 'renewals', 'display_name' => 'License Renewals', 'description' => 'Manage license renewal processes'],
                ['name' => 'compliance', 'display_name' => 'Compliance Monitoring', 'description' => 'Monitor license compliance and violations'],
            ],
            'parking' => [
                ['name' => 'zones', 'display_name' => 'Parking Zone Management', 'description' => 'Manage parking zones and regulations'],
                ['name' => 'permits', 'display_name' => 'Parking Permits', 'description' => 'Issue and manage parking permits'],
                ['name' => 'violations', 'display_name' => 'Parking Violations', 'description' => 'Record and process parking violations'],
            ],
            'markets' => [
                ['name' => 'stalls', 'display_name' => 'Market Stall Management', 'description' => 'Manage market stall allocations'],
                ['name' => 'vendors', 'display_name' => 'Vendor Management', 'description' => 'Manage market vendor information'],
                ['name' => 'revenue_collection', 'display_name' => 'Revenue Collection', 'description' => 'Collect and track market revenues'],
            ],
            'committee' => [
                ['name' => 'meetings', 'display_name' => 'Meeting Management', 'description' => 'Schedule and manage committee meetings'],
                ['name' => 'minutes', 'display_name' => 'Meeting Minutes', 'description' => 'Record and manage meeting minutes'],
                ['name' => 'resolutions', 'display_name' => 'Resolution Tracking', 'description' => 'Track committee resolutions and decisions'],
            ],
            'utilities' => [
                ['name' => 'electricity', 'display_name' => 'Electricity Management', 'description' => 'Manage electrical utility services'],
                ['name' => 'gas', 'display_name' => 'Gas Management', 'description' => 'Manage gas utility services'],
                ['name' => 'waste_management', 'display_name' => 'Waste Management', 'description' => 'Manage waste collection and disposal'],
            ],
            'property' => [
                ['name' => 'records', 'display_name' => 'Property Records', 'description' => 'Maintain property ownership records'],
                ['name' => 'valuations', 'display_name' => 'Property Valuations', 'description' => 'Manage property valuations and assessments'],
                ['name' => 'leases', 'display_name' => 'Property Leases', 'description' => 'Manage property lease agreements'],
            ],
            'survey' => [
                ['name' => 'projects', 'display_name' => 'Survey Projects', 'description' => 'Manage land surveying projects'],
                ['name' => 'equipment', 'display_name' => 'Survey Equipment', 'description' => 'Manage surveying equipment and tools'],
                ['name' => 'reports', 'display_name' => 'Survey Reports', 'description' => 'Generate and manage survey reports'],
            ],
            'inventory' => [
                ['name' => 'stock_management', 'display_name' => 'Stock Management', 'description' => 'Manage inventory stock levels'],
                ['name' => 'purchase_orders', 'display_name' => 'Purchase Orders', 'description' => 'Create and manage purchase orders'],
                ['name' => 'suppliers', 'display_name' => 'Supplier Management', 'description' => 'Manage supplier relationships'],
            ],
        ];

        foreach ($moduleFeatures as $moduleName => $features) {
            $module = \DB::table('core_modules')->where('name', $moduleName)->first();
            if ($module) {
                foreach ($features as $index => $feature) {
                    \DB::table('module_features')->insert([
                        'core_module_id' => $module->id,
                        'name' => $feature['name'],
                        'display_name' => $feature['display_name'],
                        'description' => $feature['description'],
                        'is_enabled' => true,
                        'sort_order' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
};