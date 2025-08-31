<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fdms_receipts')) {
            Schema::create('fdms_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('customer_id')->constrained('finance_customers');
            $table->foreignId('pos_terminal_id')->constrained('pos_terminals');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('ZWL');
            $table->date('receipt_date');
            $table->string('fiscal_day');
            $table->string('fiscal_number');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->string('fdms_reference')->nullable();
            $table->string('fdms_status')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['receipt_date', 'status']);
            $table->index('fiscal_day');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fdms_receipts');
    }
};
