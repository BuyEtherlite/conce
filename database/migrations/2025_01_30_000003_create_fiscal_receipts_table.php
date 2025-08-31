<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiscal_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fiscal_device_id');
            $table->string('receipt_number', 50);
            $table->string('fiscal_number', 100)->unique();
            $table->integer('receipt_counter'); // Daily counter starting from 1
            $table->text('qr_code')->nullable();
            $table->enum('receipt_type', ['sale', 'refund', 'void', 'training', 'credit_note', 'debit_note'])->default('sale');
            $table->timestamp('transaction_date');
            $table->string('customer_tin', 20)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_trade_name')->nullable();
            $table->string('customer_vat_number', 9)->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->decimal('subtotal_amount', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'bank_transfer', 'credit', 'coupon', 'other']);
            $table->string('currency_code', 3)->default('USD');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->unsignedBigInteger('operator_id');
            $table->string('verification_code', 100)->nullable();
            $table->text('digital_signature')->nullable();
            $table->text('receipt_notes')->nullable(); // For credit/debit notes
            $table->boolean('lines_tax_inclusive')->default(true);
            $table->enum('print_form', ['Receipt48', 'InvoiceA4'])->default('Receipt48');
            $table->json('receipt_data')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->boolean('is_voided')->default(false);
            $table->timestamp('zimra_transmitted_at')->nullable();
            $table->json('zimra_response')->nullable();
            $table->enum('compliance_status', ['pending', 'transmitted', 'acknowledged', 'rejected', 'error'])->default('pending');
            $table->string('zimra_receipt_number')->nullable();
            $table->integer('fiscal_day_number')->nullable();
            $table->text('rejection_reason')->nullable();

            // Credit/Debit note reference fields
            $table->unsignedBigInteger('referenced_receipt_id')->nullable();
            $table->string('referenced_receipt_global_no')->nullable();
            $table->integer('referenced_fiscal_day_no')->nullable();

            $table->timestamps();

            $table->foreign('fiscal_device_id')->references('id')->on('fiscal_devices');
            $table->foreign('operator_id')->references('id')->on('users');
            $table->foreign('referenced_receipt_id')->references('id')->on('fiscal_receipts');
            $table->index(['compliance_status', 'created_at']);
            $table->index('transaction_date');
            $table->index('receipt_type');
            $table->index(['fiscal_device_id', 'receipt_counter']);
            $table->index(['fiscal_device_id', 'fiscal_day_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiscal_receipts');
    }
};