<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_meters', function (Blueprint $table) {
            $table->id();
            $table->string('meter_number')->unique();
            $table->unsignedBigInteger('connection_id');
            $table->string('meter_type')->default('residential');
            $table->decimal('meter_size', 8, 2)->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->date('installation_date')->nullable();
            $table->date('last_reading_date')->nullable();
            $table->decimal('last_reading', 12, 3)->default(0);
            $table->decimal('current_reading', 12, 3)->default(0);
            $table->string('status')->default('active');
            $table->text('remarks')->nullable();
            $table->string('location_description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('connection_id')->references('id')->on('water_connections')->onDelete('cascade');
            $table->index(['status', 'deleted_at']);
            $table->index(['connection_id', 'deleted_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_meters');
    }
};
