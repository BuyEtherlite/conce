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
        // Drop the table if it exists, then recreate it
        Schema::dropIfExists('core_modules');
        
        Schema::create('core_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_core')->default(false);
            $table->json('features')->nullable();
            $table->json('permissions')->nullable();
            $table->string('version')->default('1.0.0');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed with default modules
        $modules = [
            [
                'name' => 'administration',
                'display_name' => 'Administration',
                'description' => 'User and department management',
                'icon' => 'users-cog',
                'is_active' => true,
                'is_core' => true,
                'features' => json_encode(['User Management', 'Department Management', 'CRM']),
                'sort_order' => 1
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance',
                'description' => 'Financial management and accounting',
                'icon' => 'chart-line',
                'is_active' => true,
                'is_core' => true,
                'features' => json_encode(['General Ledger', 'Billing', 'Receipts', 'Accounting Integration']),
                'sort_order' => 2
            ],
            [
                'name' => 'housing',
                'display_name' => 'Housing',
                'description' => 'Housing allocation and management',
                'icon' => 'home',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Waiting List', 'Allocations', 'Properties']),
                'sort_order' => 3
            ],
            [
                'name' => 'water',
                'display_name' => 'Water',
                'description' => 'Water billing and management',
                'icon' => 'tint',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Connections', 'Billing', 'Quality Testing']),
                'sort_order' => 4
            ],
            [
                'name' => 'engineering',
                'display_name' => 'Engineering',
                'description' => 'Infrastructure and project management',
                'icon' => 'tools',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Projects', 'Inspections', 'Work Orders']),
                'sort_order' => 5
            ],
            [
                'name' => 'hr',
                'display_name' => 'HR',
                'description' => 'Human resources management',
                'icon' => 'user-tie',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Employee Management', 'Payroll', 'Attendance']),
                'sort_order' => 6
            ],
            [
                'name' => 'health',
                'display_name' => 'Health',
                'description' => 'Health services and inspections',
                'icon' => 'heartbeat',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Inspections', 'Permits', 'Records']),
                'sort_order' => 7
            ],
            [
                'name' => 'licensing',
                'display_name' => 'Licensing',
                'description' => 'Business license management',
                'icon' => 'certificate',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Applications', 'Renewals', 'Compliance']),
                'sort_order' => 8
            ],
            [
                'name' => 'parking',
                'display_name' => 'Parking',
                'description' => 'Parking management and violations',
                'icon' => 'parking',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Zones', 'Permits', 'Violations']),
                'sort_order' => 9
            ],
            [
                'name' => 'markets',
                'display_name' => 'Markets',
                'description' => 'Market stall management',
                'icon' => 'store',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Stalls', 'Vendors', 'Revenue Collection']),
                'sort_order' => 10
            ],
            [
                'name' => 'committee',
                'display_name' => 'Committee',
                'description' => 'Committee and meeting management',
                'icon' => 'users',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Meetings', 'Minutes', 'Resolutions']),
                'sort_order' => 11
            ],
            [
                'name' => 'utilities',
                'display_name' => 'Utilities',
                'description' => 'Utility services management',
                'icon' => 'bolt',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Electricity', 'Gas', 'Waste Management']),
                'sort_order' => 12
            ],
            [
                'name' => 'property',
                'display_name' => 'Property',
                'description' => 'Property management and records',
                'icon' => 'building',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Records', 'Valuations', 'Leases']),
                'sort_order' => 13
            ],
            [
                'name' => 'survey',
                'display_name' => 'Survey',
                'description' => 'Survey and mapping services',
                'icon' => 'map',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Projects', 'Equipment', 'Reports']),
                'sort_order' => 14
            ],
            [
                'name' => 'inventory',
                'display_name' => 'Inventory',
                'description' => 'Inventory and stock management',
                'icon' => 'boxes',
                'is_active' => true,
                'is_core' => false,
                'features' => json_encode(['Stock Management', 'Purchase Orders', 'Suppliers']),
                'sort_order' => 15
            ]
        ];

        foreach ($modules as $module) {
            DB::table('core_modules')->insert(array_merge($module, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
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
