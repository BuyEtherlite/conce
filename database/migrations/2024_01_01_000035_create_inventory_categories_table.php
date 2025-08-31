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
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('parent_category_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('inventory_categories');
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->decimal('unit_price', 8, 2);
            $table->string('unit_of_measure');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('parking_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->integer('capacity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cemetery_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_id')->constrained('cemetery_plots');
            $table->date('maintenance_date');
            $table->string('description');
            $table->decimal('cost', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('license_applications', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_name');
            $table->string('license_type');
            $table->date('application_date');
            $table->string('status');
            $table->text('details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_categories');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('parking_zones');
        Schema::dropIfExists('cemetery_maintenance');
        Schema::dropIfExists('committee_members');
        Schema::dropIfExists('license_applications');
    }
};