<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_valuations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->date('valuation_date');
            $table->decimal('land_value', 15, 2);
            $table->decimal('improvement_value', 15, 2);
            $table->decimal('total_value', 15, 2);
            $table->string('valuation_method');
            $table->string('valuer_name');
            $table->string('valuer_registration_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_valuations');
    }
};
