<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planning_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_contact');
            $table->string('application_type'); // rezoning, subdivision, building_plan
            $table->text('description');
            $table->text('proposed_development');
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->date('application_date');
            $table->date('target_decision_date')->nullable();
            $table->string('status')->default('submitted'); // submitted, under_review, approved, rejected
            $table->text('conditions')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planning_applications');
    }
};
