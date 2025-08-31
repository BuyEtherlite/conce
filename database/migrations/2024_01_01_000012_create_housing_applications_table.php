<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('applicant_name');
            $table->string('id_number')->unique();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('current_address');
            $table->integer('household_size');
            $table->decimal('monthly_income', 10, 2);
            $table->string('employment_status');
            $table->string('preferred_area')->nullable();
            $table->string('property_type_preference')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, allocated
            $table->text('notes')->nullable();
            $table->timestamp('applied_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_applications');
    }
};
