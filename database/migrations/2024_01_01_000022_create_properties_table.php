<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_number')->unique();
            $table->string('erf_number')->nullable();
            $table->string('title_deed_number')->nullable();
            $table->string('property_type'); // residential, commercial, industrial, agricultural
            $table->text('address');
            $table->string('suburb');
            $table->string('city');
            $table->string('postal_code');
            $table->decimal('size_hectares', 10, 4)->nullable();
            $table->decimal('market_value', 15, 2)->nullable();
            $table->decimal('municipal_value', 15, 2)->nullable();
            $table->string('zoning')->nullable();
            $table->string('ownership_type'); // private, municipal, state
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
