<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_quality_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_number')->unique();
            $table->string('location');
            $table->date('test_date');
            $table->decimal('ph_level', 4, 2)->nullable();
            $table->decimal('chlorine_level', 6, 3)->nullable();
            $table->decimal('turbidity', 6, 3)->nullable();
            $table->decimal('bacteria_count', 10, 2)->nullable();
            $table->json('other_parameters')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('tested_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_quality_tests');
    }
};
