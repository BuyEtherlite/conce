<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // receipt, payment
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->date('transaction_date');
            $table->foreignId('account_id')->constrained('finance_chart_of_accounts');
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts');
            $table->string('reference_number')->nullable();
            $table->timestamp('reconciled_at')->nullable();
            $table->foreignId('reconciliation_id')->nullable()->constrained('bank_reconciliations');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['transaction_date', 'transaction_type']);
            $table->index('bank_account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
