<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stand_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_number')->unique();
            $table->foreignId('stand_id')->constrained('housing_stands')->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_id_number');
            $table->string('applicant_contact');
            $table->string('applicant_email')->nullable();
            $table->text('applicant_address');
            $table->string('intended_use'); // residential, commercial, industrial, mixed
            $table->string('business_type')->nullable(); // if commercial/industrial
            $table->decimal('allocation_amount', 12, 2);
            $table->decimal('deposit_paid', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2);
            $table->string('payment_plan'); // full_payment, installments, lease
            $table->integer('installment_months')->nullable();
            $table->decimal('monthly_installment', 10, 2)->nullable();
            $table->date('allocation_date');
            $table->date('due_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('status')->default('pending'); // pending, approved, allocated, completed, cancelled
            $table->text('conditions')->nullable();
            $table->json('required_documents')->nullable();
            $table->json('submitted_documents')->nullable();
            $table->string('approved_by')->nullable();
            $table->date('approval_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stand_allocations');
    }
};
