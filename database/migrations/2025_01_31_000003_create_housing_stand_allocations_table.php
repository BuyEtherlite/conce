<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_stand_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_number')->unique();
            $table->foreignId('stand_id')->constrained('housing_stands')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_id_number');
            $table->string('applicant_phone');
            $table->string('applicant_email')->nullable();
            $table->date('allocation_date');
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->decimal('monthly_rate', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('service_charges', 10, 2)->default(0);
            $table->enum('status', ['active', 'terminated', 'suspended', 'expired'])->default('active');
            $table->json('allocation_conditions')->nullable();
            $table->foreignId('allocated_by')->constrained('users')->onDelete('cascade');
            $table->text('approval_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_stand_allocations');
    }
};
