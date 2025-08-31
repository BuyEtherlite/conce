<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_register', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('council_id')->constrained('councils');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('asset_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // building, vehicle, equipment, infrastructure, furniture, etc.
            $table->string('type'); // specific type within category
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 15, 2);
            $table->decimal('current_value', 15, 2);
            $table->decimal('depreciation_rate', 5, 2)->default(0);
            $table->integer('useful_life_years')->default(1);
            $table->string('condition'); // excellent, good, fair, poor, critical
            $table->date('last_inspection_date')->nullable();
            $table->date('next_inspection_due')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_due')->nullable();
            $table->string('warranty_provider')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->string('supplier')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status'); // active, inactive, under_maintenance, disposed, stolen, damaged
            $table->string('disposal_reason')->nullable();
            $table->date('disposal_date')->nullable();
            $table->decimal('disposal_value', 15, 2)->nullable();
            $table->json('documents')->nullable();
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_register');
    }
};