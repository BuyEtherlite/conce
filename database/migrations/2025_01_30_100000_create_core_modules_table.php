<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('core_modules')) {
            Schema::create('core_modules', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('enabled')->default(true);
                $table->text('description')->nullable();
                $table->json('config')->nullable(); // Store additional module configurations
                $table->json('features')->nullable(); // Store enabled/disabled features within modules
                $table->json('departments')->nullable(); // Store which departments can access this module
                $table->timestamps();
            });

            // Insert default modules based on your system structure
            $defaultModules = [
                [
                    'name' => 'housing',
                    'enabled' => true,
                    'description' => 'Housing management, waiting lists, property allocations',
                    'config' => json_encode(['auto_allocation' => false]),
                    'features' => json_encode([
                        'waiting_list' => true,
                        'allocations' => true,
                        'properties' => true,
                        'tenants' => true,
                        'inspections' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'finance',
                    'enabled' => true,
                    'description' => 'Complete financial management and accounting',
                    'config' => json_encode(['multi_currency' => true, 'ipsas_compliance' => true]),
                    'features' => json_encode([
                        'accounts_payable' => true,
                        'accounts_receivable' => true,
                        'general_ledger' => true,
                        'budgets' => true,
                        'fixed_assets' => true,
                        'bank_reconciliation' => true,
                        'pos' => true,
                        'fiscalization' => false,
                        'multicurrency' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'administration',
                    'enabled' => true,
                    'description' => 'Administrative functions and CRM',
                    'config' => json_encode(['workflow_automation' => true]),
                    'features' => json_encode([
                        'crm' => true,
                        'service_requests' => true,
                        'communications' => true,
                        'reports' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'committee',
                    'enabled' => true,
                    'description' => 'Committee meetings, agendas, and minutes',
                    'config' => json_encode(['public_access' => false]),
                    'features' => json_encode([
                        'meetings' => true,
                        'agendas' => true,
                        'minutes' => true,
                        'resolutions' => true,
                        'public_portal' => false
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'water',
                    'enabled' => true,
                    'description' => 'Water connections, billing, and quality management',
                    'config' => json_encode(['auto_billing' => false]),
                    'features' => json_encode([
                        'connections' => true,
                        'billing' => true,
                        'meters' => true,
                        'quality_tests' => true,
                        'infrastructure' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'hr',
                    'enabled' => true,
                    'description' => 'Human resources and payroll management',
                    'config' => json_encode(['face_recognition' => false]),
                    'features' => json_encode([
                        'employees' => true,
                        'attendance' => true,
                        'payroll' => true,
                        'leave_management' => true,
                        'face_recognition' => false
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'engineering',
                    'enabled' => true,
                    'description' => 'Engineering projects and infrastructure management',
                    'config' => json_encode(['project_workflow' => true]),
                    'features' => json_encode([
                        'projects' => true,
                        'facilities' => true,
                        'infrastructure' => true,
                        'inspections' => true,
                        'work_orders' => true,
                        'assets' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'utilities',
                    'enabled' => true,
                    'description' => 'Utility management and infrastructure',
                    'config' => json_encode(['multi_utility' => true]),
                    'features' => json_encode([
                        'electricity' => true,
                        'gas' => true,
                        'waste_collection' => true,
                        'fleet_management' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'health',
                    'enabled' => true,
                    'description' => 'Health services and environmental health',
                    'config' => json_encode(['inspection_scheduling' => true]),
                    'features' => json_encode([
                        'inspections' => true,
                        'permits' => true,
                        'facilities' => true,
                        'food_safety' => true,
                        'environmental' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'licensing',
                    'enabled' => true,
                    'description' => 'Business licensing and permits',
                    'config' => json_encode(['auto_renewal_reminder' => true]),
                    'features' => json_encode([
                        'business_licenses' => true,
                        'operating_licenses' => true,
                        'shop_permits' => true,
                        'applications' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'parking',
                    'enabled' => true,
                    'description' => 'Parking management and violations',
                    'config' => json_encode(['mobile_enforcement' => false]),
                    'features' => json_encode([
                        'permits' => true,
                        'violations' => true,
                        'zones' => true,
                        'payments' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'markets',
                    'enabled' => true,
                    'description' => 'Market and stall management',
                    'config' => json_encode(['revenue_tracking' => true]),
                    'features' => json_encode([
                        'stalls' => true,
                        'vendors' => true,
                        'revenue_collection' => true,
                        'maintenance' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'cemeteries',
                    'enabled' => true,
                    'description' => 'Cemetery and burial management',
                    'config' => json_encode(['digital_records' => true]),
                    'features' => json_encode([
                        'plots' => true,
                        'burials' => true,
                        'maintenance' => true,
                        'reservations' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'survey',
                    'enabled' => true,
                    'description' => 'Land surveying and mapping',
                    'config' => json_encode(['gis_integration' => false]),
                    'features' => json_encode([
                        'projects' => true,
                        'equipment' => true,
                        'measurements' => true,
                        'reports' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'events',
                    'enabled' => true,
                    'description' => 'Event permits and management',
                    'config' => json_encode(['clearance_workflow' => true]),
                    'features' => json_encode([
                        'permits' => true,
                        'categories' => true,
                        'inspections' => true,
                        'clearances' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'planning',
                    'enabled' => true,
                    'description' => 'Town planning and zoning',
                    'config' => json_encode(['zoning_maps' => false]),
                    'features' => json_encode([
                        'applications' => true,
                        'zoning' => true,
                        'approvals' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'property-tax',
                    'enabled' => true,
                    'description' => 'Property tax assessment and collection',
                    'config' => json_encode(['auto_assessment' => false]),
                    'features' => json_encode([
                        'assessments' => true,
                        'valuations' => true,
                        'billing' => true,
                        'payments' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'inventory',
                    'enabled' => true,
                    'description' => 'Inventory and asset management',
                    'config' => json_encode(['barcode_scanning' => false]),
                    'features' => json_encode([
                        'items' => true,
                        'categories' => true,
                        'transactions' => true,
                        'suppliers' => true
                    ]),
                    'departments' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            foreach ($defaultModules as $module) {
                DB::table('core_modules')->insert($module);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_modules');
    }
};
