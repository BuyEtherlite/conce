<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('project_name');
            $table->string('client_name');
            $table->string('client_contact');
            $table->string('survey_type'); // cadastral, topographical, engineering, boundary
            $table->text('project_description');
            $table->text('location');
            $table->date('start_date');
            $table->date('expected_completion_date');
            $table->date('actual_completion_date')->nullable();
            $table->string('surveyor_name');
            $table->string('surveyor_registration');
            $table->decimal('project_fee', 15, 2);
            $table->string('status')->default('active'); // active, completed, cancelled, on_hold
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_projects');
    }
};
