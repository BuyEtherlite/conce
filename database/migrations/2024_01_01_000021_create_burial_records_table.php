<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('burial_records', function (Blueprint $table) {
            $table->id();
            $table->string('burial_number')->unique();
            $table->foreignId('plot_id')->constrained('cemetery_plots')->onDelete('cascade');
            $table->string('deceased_name');
            $table->string('deceased_id_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_death');
            $table->date('burial_date');
            $table->string('cause_of_death')->nullable();
            $table->string('next_of_kin_name');
            $table->string('next_of_kin_contact');
            $table->string('undertaker')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('burial_records');
    }
};
