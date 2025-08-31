<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing table completely to avoid conflicts
        Schema::dropIfExists('finance_general_ledger');
        
        Schema::create('finance_general_ledger', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('account_code');
            $table->date('transaction_date');
            $table->text('description');
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->string('source_module')->nullable();
            $table->string('source_document_type')->nullable();
            $table->unsignedBigInteger('source_document_id')->nullable();
            $table->string('program_code')->nullable();
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('account_code')->references('account_code')->on('finance_chart_of_accounts');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        // Create indexes after table is created
        DB::statement('CREATE INDEX IF NOT EXISTS finance_general_ledger_transaction_date_status_idx ON finance_general_ledger (transaction_date, status)');
        DB::statement('CREATE INDEX IF NOT EXISTS finance_general_ledger_account_code_status_idx ON finance_general_ledger (account_code, status)');
        DB::statement('CREATE INDEX IF NOT EXISTS finance_general_ledger_transaction_id_idx ON finance_general_ledger (transaction_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_general_ledger');
    }
};
