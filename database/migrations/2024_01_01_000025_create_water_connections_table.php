<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('water_connections', function (Blueprint $table) {
            $table->id();
            $table->string('connection_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('meter_number')->unique();
            $table->string('meter_size');
            $table->date('connection_date');
            $table->string('connection_type'); // domestic, commercial, industrial
            $table->decimal('deposit_paid', 10, 2);
            $table->decimal('connection_fee', 10, 2);
            $table->string('status')->default('active'); // active, disconnected, suspended
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Water Bills Table
        Schema::create('water_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('water_connections');
            $table->decimal('amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_bills');
        Schema::dropIfExists('water_connections');
    }
};