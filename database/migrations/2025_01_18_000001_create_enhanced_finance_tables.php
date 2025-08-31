<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Zimbabwe National Chart of Accounts
        Schema::create('zimbabwe_chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 20)->unique();
            $table->string('account_name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->string('account_category', 50);
            $table->string('account_subcategory', 50)->nullable();
            $table->integer('account_level');
            $table->string('parent_account_code', 20)->nullable();
            $table->boolean('is_control_account')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('government_classification', 50)->nullable();
            $table->string('ipsas_classification', 50)->nullable();
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['account_type', 'account_category']);
            $table->index('parent_account_code');
        });

        // Currencies for multicurrency support
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3)->unique();
            $table->string('currency_name');
            $table->string('currency_symbol', 10);
            $table->decimal('exchange_rate', 10, 6);
            $table->boolean('is_base_currency')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('decimal_places')->default(2);
            $table->decimal('rounding_precision', 8, 6)->default(0.01);
            $table->timestamps();
        });

        // Exchange Rate History
        Schema::create('exchange_rate_histories', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3);
            $table->decimal('exchange_rate', 10, 6);
            $table->date('effective_date');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['currency_code', 'effective_date']);
        });

        // FDMS Receipts
        Schema::create('fdms_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('fiscal_receipt_number')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->date('receipt_date');
            $table->timestamp('receipt_time');
            $table->enum('payment_method', ['cash', 'card', 'mobile', 'bank_transfer', 'cheque']);
            $table->string('currency_code', 3);
            $table->decimal('exchange_rate', 10, 6)->default(1);
            $table->decimal('subtotal_amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_tendered', 15, 2);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->string('fiscal_device_id')->nullable();
            $table->text('fiscal_signature')->nullable();
            $table->text('qr_code')->nullable();
            $table->string('verification_code')->nullable();
            $table->boolean('fdms_transmitted')->default(false);
            $table->timestamp('fdms_transmission_date')->nullable();
            $table->json('fdms_response')->nullable();
            $table->enum('status', ['active', 'voided', 'cancelled'])->default('active');
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->unsignedBigInteger('original_receipt_id')->nullable();
            $table->json('items');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('cashier_id')->references('id')->on('users');
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->foreign('original_receipt_id')->references('id')->on('fdms_receipts')->onDelete('set null');
            $table->index(['receipt_date', 'status']);
            $table->index('fdms_transmitted');
        });

        // Fiscal Devices
        Schema::create('fiscal_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('device_name');
            $table->string('device_type');
            $table->string('serial_number');
            $table->string('manufacturer');
            $table->string('firmware_version')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();
        });

        // Program Based Budgets
        Schema::create('program_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('program_code');
            $table->string('program_name');
            $table->text('program_description')->nullable();
            $table->year('budget_year');
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('committed_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'active', 'closed']);
            $table->unsignedBigInteger('responsible_officer');
            $table->timestamps();
            
            $table->foreign('responsible_officer')->references('id')->on('users');
            $table->index(['budget_year', 'status']);
        });

        // Cashbook Entries
        Schema::create('cashbook_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->enum('entry_type', ['receipt', 'payment']);
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency_code', 3);
            $table->decimal('exchange_rate', 10, 6)->default(1);
            $table->enum('payment_method', ['cash', 'cheque', 'electronic', 'mobile']);
            $table->string('bank_account_id')->nullable();
            $table->string('account_code');
            $table->unsignedBigInteger('created_by');
            $table->enum('status', ['pending', 'cleared', 'cancelled']);
            $table->timestamps();
            
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->index(['transaction_date', 'entry_type']);
        });

        // Debtors
        Schema::create('debtors', function (Blueprint $table) {
            $table->id();
            $table->string('debtor_number')->unique();
            $table->string('debtor_name');
            $table->string('debtor_type'); // individual, business, government
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->integer('payment_terms')->default(30); // days
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Debtor Transactions
        Schema::create('debtor_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debtor_id');
            $table->string('transaction_number');
            $table->enum('transaction_type', ['invoice', 'payment', 'credit_note', 'adjustment']);
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency_code', 3);
            $table->string('reference_number')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue', 'written_off']);
            $table->timestamps();
            
            $table->foreign('debtor_id')->references('id')->on('debtors')->onDelete('cascade');
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->index(['debtor_id', 'status']);
            $table->index('due_date');
        });

        // Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->enum('voucher_type', ['payment', 'receipt', 'journal']);
            $table->date('voucher_date');
            $table->text('description');
            $table->decimal('total_amount', 15, 2);
            $table->string('currency_code', 3);
            $table->string('payee_name')->nullable();
            $table->enum('payment_method', ['cash', 'cheque', 'electronic', 'mobile'])->nullable();
            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('prepared_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'paid', 'cancelled']);
            $table->timestamps();
            
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->foreign('prepared_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['voucher_date', 'status']);
        });

        // Voucher Lines
        Schema::create('voucher_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_id');
            $table->string('account_code');
            $table->text('description');
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->index('voucher_id');
        });

        // Fixed Assets
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_number')->unique();
            $table->string('asset_name');
            $table->text('asset_description')->nullable();
            $table->string('asset_category');
            $table->string('asset_location')->nullable();
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 15, 2);
            $table->string('currency_code', 3);
            $table->decimal('depreciation_rate', 5, 2);
            $table->enum('depreciation_method', ['straight_line', 'reducing_balance', 'units_production']);
            $table->integer('useful_life_years');
            $table->decimal('residual_value', 15, 2)->default(0);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->decimal('current_value', 15, 2);
            $table->string('custodian')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->enum('status', ['active', 'disposed', 'written_off'])->default('active');
            $table->date('disposal_date')->nullable();
            $table->decimal('disposal_amount', 15, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('currency_code')->references('currency_code')->on('currencies');
            $table->index(['asset_category', 'status']);
        });

        // Asset Depreciation History
        Schema::create('asset_depreciation_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixed_asset_id');
            $table->year('depreciation_year');
            $table->integer('depreciation_month');
            $table->decimal('depreciation_amount', 15, 2);
            $table->decimal('accumulated_depreciation', 15, 2);
            $table->decimal('book_value', 15, 2);
            $table->timestamps();
            
            $table->foreign('fixed_asset_id')->references('id')->on('fixed_assets')->onDelete('cascade');
            $table->index(['fixed_asset_id', 'depreciation_year', 'depreciation_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_depreciation_history');
        Schema::dropIfExists('fixed_assets');
        Schema::dropIfExists('voucher_lines');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('debtor_transactions');
        Schema::dropIfExists('debtors');
        Schema::dropIfExists('cashbook_entries');
        Schema::dropIfExists('program_budgets');
        Schema::dropIfExists('fiscal_devices');
        Schema::dropIfExists('fdms_receipts');
        Schema::dropIfExists('exchange_rate_histories');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('zimbabwe_chart_of_accounts');
    }
};
