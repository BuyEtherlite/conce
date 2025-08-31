<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infrastructure_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_number')->unique();
            $table->string('asset_name');
            $table->string('asset_type'); // road, bridge, building, pipeline, electrical
            $table->string('category');
            $table->text('location');
            $table->text('description')->nullable();
            $table->date('installation_date')->nullable();
            $table->decimal('original_cost', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->string('condition'); // excellent, good, fair, poor, critical
            $table->date('last_inspection_date')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->integer('expected_life_years')->nullable();
            $table->string('status')->default('active'); // active, inactive, decommissioned
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_assets');
    }
};
