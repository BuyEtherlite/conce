<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('housing_applications')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('housing_properties')->onDelete('cascade');
            $table->date('allocation_date');
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->decimal('monthly_rent', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->string('status')->default('active'); // active, terminated, expired
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_allocations');
    }
};
