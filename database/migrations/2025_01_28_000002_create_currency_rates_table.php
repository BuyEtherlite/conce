<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the existing table if it exists to recreate with proper structure
        Schema::dropIfExists('currency_rates');
        
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('exchange_rate', 15, 6);
            $table->date('effective_date');
            $table->enum('rate_type', ['buy', 'sell', 'mid'])->default('mid');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['currency_id', 'effective_date']);
            $table->index(['effective_date', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('currency_rates');
    }
};
