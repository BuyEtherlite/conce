<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_stand_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('sector_type'); // residential, commercial, industrial, mixed_use, recreational
            $table->string('location');
            $table->decimal('total_area_hectares', 10, 4)->nullable();
            $table->integer('total_stands');
            $table->integer('allocated_stands')->default(0);
            $table->integer('available_stands')->default(0);
            $table->decimal('stand_size_min_sqm', 8, 2)->nullable();
            $table->decimal('stand_size_max_sqm', 8, 2)->nullable();
            $table->decimal('price_per_sqm', 8, 2)->nullable();
            $table->json('utilities_available')->nullable(); // water, electricity, sewer, internet
            $table->json('amenities')->nullable(); // schools, hospitals, shopping, transport
            $table->string('development_status')->default('planned'); // planned, under_development, developed, occupied
            $table->string('zoning_approval')->nullable();
            $table->date('development_start_date')->nullable();
            $table->date('development_completion_date')->nullable();
            $table->text('special_conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_stand_areas');
    }
};
