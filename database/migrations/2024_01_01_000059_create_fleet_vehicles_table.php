<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fleet_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('vehicle_type'); // car, truck, bus, motorcycle, tractor
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('assigned_driver')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('current_odometer')->default(0);
            $table->date('license_expiry_date');
            $table->date('insurance_expiry_date');
            $table->string('status')->default('active'); // active, maintenance, decommissioned
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fleet_vehicles');
    }
};
