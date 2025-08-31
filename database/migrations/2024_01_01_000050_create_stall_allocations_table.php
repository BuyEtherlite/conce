<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stall_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id')->constrained('market_stalls')->onDelete('cascade');
            $table->string('trader_name');
            $table->string('trader_id_number');
            $table->string('trader_contact');
            $table->string('business_type');
            $table->date('allocation_date');
            $table->string('allocation_type'); // daily, weekly, monthly, permanent
            $table->decimal('rental_amount', 10, 2);
            $table->decimal('deposit_paid', 10, 2);
            $table->string('status')->default('active'); // active, terminated, suspended
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stall_allocations');
    }
};
