<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // hall, pool, sports_field, park
            $table->string('location');
            $table->integer('capacity');
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->decimal('daily_rate', 8, 2)->default(0);
            $table->string('status')->default('available'); // available, maintenance, closed
            $table->json('operating_hours')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
