<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_collection_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name');
            $table->string('route_code')->unique();
            $table->text('route_description');
            $table->json('collection_days'); // ['monday', 'wednesday', 'friday']
            $table->time('start_time');
            $table->time('estimated_completion_time');
            $table->integer('estimated_households');
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('fleet_vehicles');
            $table->string('assigned_driver')->nullable();
            $table->string('status')->default('active'); // active, inactive, suspended
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_collection_routes');
    }
};
