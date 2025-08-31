<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_rates', function (Blueprint $table) {
            $table->id();
            $table->string('rate_name');
            $table->enum('connection_type', ['residential', 'commercial', 'industrial', 'institutional']);
            $table->decimal('base_charge', 10, 2)->default(0);
            $table->decimal('rate_per_unit', 10, 2);
            $table->decimal('minimum_charge', 10, 2)->default(0);
            $table->date('effective_date');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['connection_type', 'effective_date', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_rates');
    }
};
