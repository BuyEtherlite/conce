<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infrastructure_maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('council_id')->constrained('councils');
            $table->foreignId('asset_id')->nullable()->constrained('asset_register');
            $table->string('request_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('type'); // preventive, corrective, emergency
            $table->string('category'); // electrical, plumbing, hvac, structural, etc.
            $table->string('priority'); // low, medium, high, critical
            $table->string('status'); // open, assigned, in_progress, completed, cancelled, on_hold
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('reported_by');
            $table->string('reporter_contact')->nullable();
            $table->date('reported_date');
            $table->date('required_by_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->date('assigned_date')->nullable();
            $table->date('started_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->text('work_performed')->nullable();
            $table->json('materials_used')->nullable();
            $table->json('photos_before')->nullable();
            $table->json('photos_after')->nullable();
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_maintenance_requests');
    }
};
