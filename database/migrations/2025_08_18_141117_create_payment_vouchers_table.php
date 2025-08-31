<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->onDelete('set null');
            $table->string('payee_name');
            $table->text('payee_address')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('purpose');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->string('invoice_number')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained('ap_vendors')->onDelete('set null');
            $table->foreignId('bill_id')->nullable()->constrained('ap_bills')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['payment_date']);
            $table->index(['status']);
            $table->index(['voucher_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
