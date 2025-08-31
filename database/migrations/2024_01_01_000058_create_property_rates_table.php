<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('financial_year');
            $table->decimal('municipal_value', 15, 2);
            $table->decimal('rate_cent_amount', 8, 4); // rate per R100
            $table->decimal('annual_rates', 15, 2);
            $table->decimal('refuse_charges', 10, 2)->default(0);
            $table->decimal('sewerage_charges', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('outstanding_balance', 15, 2);
            $table->string('status')->default('current'); // current, arrears, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_rates');
    }
};
