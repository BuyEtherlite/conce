<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if table exists
        if (!Schema::hasTable('cemetery_maintenance')) {
            Schema::create('cemetery_maintenance', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
                $table->date('scheduled_date');
                $table->date('completed_date')->nullable();
                $table->decimal('estimated_cost', 10, 2)->nullable();
                $table->decimal('actual_cost', 10, 2)->nullable();
                $table->string('assigned_to')->nullable();
                $table->text('maintenance_notes')->nullable();
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('maintenance_type', ['cleaning', 'repair', 'landscaping', 'security', 'other'])->default('other');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cemetery_maintenance');
    }
};
