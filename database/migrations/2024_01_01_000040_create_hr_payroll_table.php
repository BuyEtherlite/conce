<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('hr_employees')->onDelete('cascade');
            $table->string('pay_period'); // monthly, weekly
            $table->date('pay_date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('tax_deduction', 10, 2)->default(0);
            $table->decimal('uif_deduction', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->string('status')->default('draft'); // draft, processed, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_payroll');
    }
};
