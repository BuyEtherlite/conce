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
        // Drop the table if it exists to recreate it with correct structure
        Schema::dropIfExists('module_features');
        
        Schema::create('module_features', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('feature_name');
            $table->string('feature_key')->unique();
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->json('permissions')->nullable(); // Store required permissions
            $table->timestamps();
            
            $table->index(['module_name', 'enabled']);
        });

        // Insert default module features
        $features = [
            // Housing Module Features
            ['module_name' => 'housing', 'feature_name' => 'Property Management', 'feature_key' => 'housing.properties', 'description' => 'Manage housing properties', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'housing', 'feature_name' => 'Housing Applications', 'feature_key' => 'housing.applications', 'description' => 'Process housing applications', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'housing', 'feature_name' => 'Waiting List Management', 'feature_key' => 'housing.waiting_list', 'description' => 'Manage housing waiting list', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'housing', 'feature_name' => 'Tenant Management', 'feature_key' => 'housing.tenants', 'description' => 'Manage tenants', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'housing', 'feature_name' => 'Allocations', 'feature_key' => 'housing.allocations', 'description' => 'Manage housing allocations', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Finance Module Features
            ['module_name' => 'finance', 'feature_name' => 'Accounts Receivable', 'feature_key' => 'finance.ar', 'description' => 'Manage accounts receivable', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'Accounts Payable', 'feature_key' => 'finance.ap', 'description' => 'Manage accounts payable', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'General Ledger', 'feature_key' => 'finance.gl', 'description' => 'Manage general ledger', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'Bank Management', 'feature_key' => 'finance.bank', 'description' => 'Manage bank accounts', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'Fixed Assets', 'feature_key' => 'finance.assets', 'description' => 'Manage fixed assets', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'Budgeting', 'feature_key' => 'finance.budgets', 'description' => 'Manage budgets', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'Fiscalization', 'feature_key' => 'finance.fiscalization', 'description' => 'Manage fiscal receipts', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'finance', 'feature_name' => 'POS System', 'feature_key' => 'finance.pos', 'description' => 'Point of sale system', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Administration Module Features
            ['module_name' => 'administration', 'feature_name' => 'User Management', 'feature_key' => 'admin.users', 'description' => 'Manage system users', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'administration', 'feature_name' => 'Department Management', 'feature_key' => 'admin.departments', 'description' => 'Manage departments', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'administration', 'feature_name' => 'Office Management', 'feature_key' => 'admin.offices', 'description' => 'Manage offices', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'administration', 'feature_name' => 'CRM System', 'feature_key' => 'admin.crm', 'description' => 'Customer relationship management', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            
            // Committee Module Features
            ['module_name' => 'committee', 'feature_name' => 'Committee Management', 'feature_key' => 'committee.committees', 'description' => 'Manage committees', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'committee', 'feature_name' => 'Meeting Management', 'feature_key' => 'committee.meetings', 'description' => 'Manage meetings', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['module_name' => 'committee', 'feature_name' => 'Minutes & Resolutions', 'feature_key' => 'committee.minutes', 'description' => 'Manage meeting minutes and resolutions', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($features as $feature) {
            $exists = DB::table('module_features')->where('feature_key', $feature['feature_key'])->exists();
            if (!$exists) {
                DB::table('module_features')->insert($feature);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_features');
    }
};
