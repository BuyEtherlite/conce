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
        Schema::create('bill_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('municipal_bills')->onDelete('cascade');
            $table->enum('type', ['first_reminder', 'second_reminder', 'final_notice']);
            $table->date('sent_date');
            $table->string('method')->default('email'); // email, sms, postal
            $table->text('message')->nullable();
            $table->timestamps();
            
            $table->index(['bill_id']);
            $table->index(['sent_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_reminders');
    }
};
