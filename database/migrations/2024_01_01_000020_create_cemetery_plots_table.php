<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cemetery_plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cemetery_id')->constrained()->onDelete('cascade');
            $table->string('plot_number')->unique();
            $table->string('section');
            $table->string('row_number');
            $table->string('plot_type'); // single, double, family
            $table->decimal('size_length', 8, 2);
            $table->decimal('size_width', 8, 2);
            $table->decimal('price', 10, 2);
            $table->string('status')->default('available'); // available, reserved, occupied
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cemetery_plots');
    }
};
