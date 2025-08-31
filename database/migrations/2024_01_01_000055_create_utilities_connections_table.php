<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilities_connections', function (Blueprint $table) {
            $table->id();
            $table->string('connection_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('utility_type'); // electricity, gas, fiber
            $table->string('meter_number')->nullable();
            $table->string('connection_type'); // domestic, commercial, industrial
            $table->date('connection_date');
            $table->decimal('connection_fee', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->string('status')->default('active'); // active, disconnected, suspended
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilities_connections');
    }
};
