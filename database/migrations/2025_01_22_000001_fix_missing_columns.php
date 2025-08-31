<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Fix councils table - change 'active' to 'is_active'
        if (Schema::hasTable('councils') && Schema::hasColumn('councils', 'active') && !Schema::hasColumn('councils', 'is_active')) {
            Schema::table('councils', function (Blueprint $table) {
                $table->renameColumn('active', 'is_active');
            });
        }

        // Fix departments table - add is_active column if missing
        if (Schema::hasTable('departments') && !Schema::hasColumn('departments', 'is_active')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }

        // Fix offices table - add is_active column if missing
        if (Schema::hasTable('offices') && !Schema::hasColumn('offices', 'is_active')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('council_id');
            });
        }

        // Fix housing_waiting_list table - add approval_date if missing
        if (Schema::hasTable('housing_waiting_list') && !Schema::hasColumn('housing_waiting_list', 'approval_date')) {
            Schema::table('housing_waiting_list', function (Blueprint $table) {
                $table->date('approval_date')->nullable();
            });
        }

        // Fix water_meter_readings table - add status if missing
        if (Schema::hasTable('water_meter_readings') && !Schema::hasColumn('water_meter_readings', 'status')) {
            Schema::table('water_meter_readings', function (Blueprint $table) {
                $table->enum('status', ['pending', 'verified', 'billed'])->default('pending');
            });
        }

        // Fix engineering_projects table - add deleted_at if missing
        if (Schema::hasTable('engineering_projects') && !Schema::hasColumn('engineering_projects', 'deleted_at')) {
            Schema::table('engineering_projects', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to planning_applications if missing
        if (Schema::hasTable('planning_applications') && !Schema::hasColumn('planning_applications', 'deleted_at')) {
            Schema::table('planning_applications', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to water_bills if missing
        if (Schema::hasTable('water_bills') && !Schema::hasColumn('water_bills', 'deleted_at')) {
            Schema::table('water_bills', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add minimum_stock to inventory_items if missing
        if (Schema::hasTable('inventory_items') && !Schema::hasColumn('inventory_items', 'minimum_stock')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                $table->integer('minimum_stock')->default(0)->after('current_stock');
            });
        }

        // Fix parking_zones - add is_active if missing
        if (Schema::hasTable('parking_zones') && !Schema::hasColumn('parking_zones', 'is_active')) {
            Schema::table('parking_zones', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }

        // Add deleted_at to markets if missing
        if (Schema::hasTable('markets') && !Schema::hasColumn('markets', 'deleted_at')) {
            Schema::table('markets', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Create electricity_connections table if missing
        if (!Schema::hasTable('electricity_connections')) {
            Schema::create('electricity_connections', function (Blueprint $table) {
                $table->id();
                $table->string('connection_number')->unique();
                $table->foreignId('customer_id')->constrained('customers');
                $table->string('property_address');
                $table->enum('connection_type', ['residential', 'commercial', 'industrial']);
                $table->decimal('load_capacity', 8, 2);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->date('connection_date');
                $table->decimal('monthly_charge', 8, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create gas_connections table if missing
        if (!Schema::hasTable('gas_connections')) {
            Schema::create('gas_connections', function (Blueprint $table) {
                $table->id();
                $table->string('connection_number')->unique();
                $table->foreignId('customer_id')->constrained('customers');
                $table->string('property_address');
                $table->enum('connection_type', ['residential', 'commercial', 'industrial']);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->date('connection_date');
                $table->decimal('monthly_charge', 8, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create parking_spaces table if missing
        if (!Schema::hasTable('parking_spaces')) {
            Schema::create('parking_spaces', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parking_zone_id')->constrained('parking_zones');
                $table->string('space_number');
                $table->enum('space_type', ['standard', 'disabled', 'loading', 'motorcycle']);
                $table->enum('status', ['available', 'occupied', 'reserved', 'out_of_order'])->default('available');
                $table->boolean('is_active')->default(true);
                $table->decimal('hourly_rate', 5, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Create market_revenue_collections table if missing
        if (!Schema::hasTable('market_revenue_collections')) {
            Schema::create('market_revenue_collections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('market_id')->constrained('markets');
                $table->foreignId('stall_id')->constrained('market_stalls');
                $table->string('receipt_number')->unique();
                $table->decimal('amount_paid', 10, 2);
                $table->enum('payment_type', ['rental', 'permit', 'fine', 'other']);
                $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending');
                $table->date('paid_date')->nullable();
                $table->enum('payment_method', ['cash', 'card', 'eft', 'mobile']);
                $table->foreignId('collected_by')->constrained('users');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Create missing tables
        if (!Schema::hasTable('committee_minutes')) {
            Schema::create('committee_minutes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('committee_id')->constrained('committee_committees')->onDelete('cascade');
                $table->foreignId('meeting_id')->constrained('committee_meetings')->onDelete('cascade');
                $table->text('content');
                $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
                $table->foreignId('created_by')->constrained('users');
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_categories')) {
            Schema::create('event_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('base_fee', 10, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Reverse the changes
        if (Schema::hasTable('councils') && Schema::hasColumn('councils', 'is_active')) {
            Schema::table('councils', function (Blueprint $table) {
                $table->renameColumn('is_active', 'active');
            });
        }

        if (Schema::hasTable('offices') && Schema::hasColumn('offices', 'is_active')) {
            Schema::table('offices', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('planning_applications') && Schema::hasColumn('planning_applications', 'deleted_at')) {
            Schema::table('planning_applications', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('water_bills') && Schema::hasColumn('water_bills', 'deleted_at')) {
            Schema::table('water_bills', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('inventory_items') && Schema::hasColumn('inventory_items', 'minimum_stock')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                $table->dropColumn('minimum_stock');
            });
        }

        if (Schema::hasTable('parking_zones') && Schema::hasColumn('parking_zones', 'is_active')) {
            Schema::table('parking_zones', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('markets') && Schema::hasColumn('markets', 'deleted_at')) {
            Schema::table('markets', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        Schema::dropIfExists('committee_minutes');
        Schema::dropIfExists('event_categories');
    }
};
