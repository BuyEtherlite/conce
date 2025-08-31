<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gate_takings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time_period_start');
            $table->time('time_period_end');
            $table->integer('adult_tickets');
            $table->decimal('adult_price', 8, 2);
            $table->integer('child_tickets');
            $table->decimal('child_price', 8, 2);
            $table->integer('senior_tickets');
            $table->decimal('senior_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('collected_by');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_takings');
    }
};
