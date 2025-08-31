<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licensing_business_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_number')->unique();
            $table->string('business_name');
            $table->string('business_type');
            $table->string('owner_name');
            $table->string('owner_id_number');
            $table->text('business_address');
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->decimal('license_fee', 10, 2);
            $table->string('status')->default('active'); // active, expired, suspended, cancelled
            $table->text('conditions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licensing_business_licenses');
    }
};
