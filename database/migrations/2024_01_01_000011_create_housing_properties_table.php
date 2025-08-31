<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_number')->unique();
            $table->string('property_type'); // house, flat, maisonette
            $table->string('address');
            $table->string('suburb');
            $table->string('city');
            $table->string('postal_code');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('size_sqm', 8, 2)->nullable();
            $table->decimal('rental_amount', 10, 2);
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_properties');
    }
};
