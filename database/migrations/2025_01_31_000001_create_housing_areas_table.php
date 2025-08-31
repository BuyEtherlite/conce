<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('location');
            $table->integer('total_stands')->default(0);
            $table->integer('available_stands')->default(0);
            $table->enum('area_type', ['residential', 'commercial', 'industrial', 'mixed'])->default('residential');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->foreignId('council_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('office_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_areas');
    }
};
