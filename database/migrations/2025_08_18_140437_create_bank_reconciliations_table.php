<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->string('reconciliation_number')->unique();
            $table->foreignId('bank_account_id')->constrained('bank_accounts');
            $table->foreignId('bank_statement_id')->nullable()->constrained('bank_statements');
            $table->date('reconciliation_date');
            $table->date('statement_date');
            $table->decimal('statement_balance', 15, 2);
            $table->decimal('book_balance', 15, 2);
            $table->decimal('outstanding_deposits', 15, 2)->default(0);
            $table->decimal('outstanding_checks', 15, 2)->default(0);
            $table->decimal('bank_charges', 15, 2)->default(0);
            $table->decimal('interest_earned', 15, 2)->default(0);
            $table->decimal('adjusted_balance', 15, 2);
            $table->decimal('variance', 15, 2)->default(0);
            $table->enum('status', ['pending', 'in_progress', 'reconciled', 'discrepancy'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('prepared_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['reconciliation_date', 'status']);
            $table->index('bank_account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliations');
    }
};
