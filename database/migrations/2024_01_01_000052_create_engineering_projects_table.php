<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineering_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('project_name');
            $table->string('project_type'); // roads, water, sewer, electricity, buildings
            $table->text('description');
            $table->text('location');
            $table->decimal('estimated_cost', 15, 2);
            $table->decimal('actual_cost', 15, 2)->nullable();
            $table->date('start_date');
            $table->date('planned_completion_date');
            $table->date('actual_completion_date')->nullable();
            $table->string('project_manager');
            $table->string('contractor')->nullable();
            $table->string('status')->default('planning'); // planning, active, completed, cancelled, on_hold
            $table->integer('completion_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineering_projects');
    }
};