<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cemetery_sections', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('council_id')->constrained('councils');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('total_plots');
            $table->integer('available_plots');
            $table->integer('occupied_plots')->default(0);
            $table->decimal('plot_size_length', 8, 2);
            $table->decimal('plot_size_width', 8, 2);
            $table->decimal('standard_fee', 10, 2);
            $table->string('status')->default('active'); // active, inactive, full
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cemetery_sections');
    }
};
