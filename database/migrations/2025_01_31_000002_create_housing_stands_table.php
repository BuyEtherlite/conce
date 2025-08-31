<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_stands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stand_area_id')->constrained('housing_stand_areas')->onDelete('cascade');
            $table->string('stand_number');
            $table->string('stand_code')->unique();
            $table->decimal('size_sqm', 10, 2);
            $table->decimal('price_total', 12, 2);
            $table->decimal('price_per_sqm', 8, 2);
            $table->string('coordinates')->nullable(); // GPS coordinates
            $table->string('facing_direction')->nullable(); // north, south, east, west
            $table->string('corner_stand')->default('no'); // yes, no
            $table->string('slope_grade')->nullable(); // flat, gentle, steep
            $table->json('utilities_connected')->nullable(); // water, electricity, sewer
            $table->string('access_road_type')->nullable(); // tarred, gravel, dirt
            $table->decimal('road_frontage_meters', 8, 2)->nullable();
            $table->string('status')->default('available'); // available, allocated, reserved, under_development
            $table->text('special_features')->nullable();
            $table->text('restrictions')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['stand_area_id', 'stand_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_stands');
    }
};
