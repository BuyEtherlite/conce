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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('market_type'); // fresh_produce, flea, informal
            $table->integer('total_stalls');
            $table->integer('occupied_stalls')->default(0);
            $table->json('operating_days');
            $table->time('opening_time');
            $table->time('closing_time');
            $table->string('market_manager');
            $table->string('contact_phone');
            $table->string('status')->default('active');
            $table->text('facilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};