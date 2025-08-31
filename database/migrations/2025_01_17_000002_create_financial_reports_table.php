<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->string('report_name');
            $table->date('report_date');
            $table->json('report_data')->nullable();
            $table->enum('status', ['generating', 'generated', 'failed'])->default('generating');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['report_type', 'report_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
