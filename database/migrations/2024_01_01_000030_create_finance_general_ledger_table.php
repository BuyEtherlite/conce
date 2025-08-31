<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_general_ledger', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('account_id')->constrained('finance_chart_of_accounts')->onDelete('cascade');
            $table->date('transaction_date');
            $table->string('transaction_type'); // debit, credit
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->string('reference_number')->nullable();
            $table->string('source_document')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_general_ledger');
    }
};
