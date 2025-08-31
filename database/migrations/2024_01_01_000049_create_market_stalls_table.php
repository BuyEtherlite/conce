<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_stalls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained()->onDelete('cascade');
            $table->string('stall_number');
            $table->string('section');
            $table->decimal('size_sqm', 8, 2);
            $table->decimal('daily_rate', 8, 2);
            $table->decimal('monthly_rate', 8, 2);
            $table->string('stall_type'); // covered, open, cold_storage
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_stalls');
    }
};
