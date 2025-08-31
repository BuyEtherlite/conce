<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name');
            $table->string('financial_year');
            $table->foreignId('account_id')->constrained('finance_chart_of_accounts')->onDelete('cascade');
            $table->decimal('budgeted_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->decimal('variance', 15, 2)->default(0);
            $table->string('period'); // monthly, quarterly, annual
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('draft'); // draft, approved, locked
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_budgets');
    }
};
