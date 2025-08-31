<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->string('statement_number')->unique();
            $table->foreignId('bank_account_id')->constrained('bank_accounts');
            $table->date('statement_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('opening_balance', 15, 2);
            $table->decimal('closing_balance', 15, 2);
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'processed', 'reconciled'])->default('pending');
            $table->timestamps();
            
            $table->index(['statement_date', 'bank_account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_statements');
    }
};
