<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_leases', function (Blueprint $table) {
            $table->id();
            $table->string('lease_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('lessee_name');
            $table->string('lessee_id_number');
            $table->string('lessee_contact');
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->decimal('monthly_rental', 10, 2);
            $table->decimal('annual_escalation_percentage', 5, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2);
            $table->string('lease_purpose'); // residential, commercial, agricultural
            $table->text('special_conditions')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_leases');
    }
};
