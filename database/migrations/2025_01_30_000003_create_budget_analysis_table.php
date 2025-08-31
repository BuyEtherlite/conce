<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_budget_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('finance_budgets')->onDelete('cascade');
            $table->string('period')->nullable();
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->decimal('variance', 15, 2)->default(0);
            $table->decimal('variance_percent', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('analyzed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['budget_id', 'period']);
            $table->index('analyzed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_budget_analysis');
    }
};
