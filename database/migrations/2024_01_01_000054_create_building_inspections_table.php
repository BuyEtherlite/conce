<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('building_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('inspection_type'); // building_plan, occupancy, compliance, final
            $table->date('inspection_date');
            $table->string('inspector_name');
            $table->string('inspection_stage'); // foundation, structure, electrical, plumbing, final
            $table->text('findings');
            $table->string('result'); // passed, failed, conditional
            $table->text('defects_noted')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('re_inspection_date')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('building_inspections');
    }
};
