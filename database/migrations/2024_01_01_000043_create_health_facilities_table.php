<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // clinic, hospital, pharmacy, laboratory
            $table->text('address');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('license_number')->unique();
            $table->date('license_expiry_date');
            $table->json('services_offered')->nullable();
            $table->string('status')->default('active'); // active, suspended, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_facilities');
    }
};
