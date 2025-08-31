<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number')->unique();
            $table->foreignId('facility_id')->nullable()->constrained('health_facilities');
            $table->string('inspection_type'); // routine, complaint, follow_up
            $table->date('inspection_date');
            $table->string('inspector_name');
            $table->text('findings');
            $table->string('compliance_status'); // compliant, non_compliant, conditional
            $table->text('recommendations')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_inspections');
    }
};
