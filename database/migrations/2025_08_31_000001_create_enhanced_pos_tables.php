<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhanced payments table for POS transactions
        if (!Schema::hasTable('pos_payments')) {
            Schema::create('pos_payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_number')->unique();
                $table->dateTime('payment_date');
                $table->decimal('total_amount', 15, 2);
                $table->string('payment_method'); // cash, card, mobile_money, bank_transfer
                $table->foreignId('terminal_id')->constrained('pos_terminals');
                $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
                $table->json('payment_details')->nullable(); // Store additional payment gateway details
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
                
                $table->index(['payment_date', 'status']);
                $table->index(['terminal_id', 'payment_date']);
                $table->index('payment_method');
            });
        }

        // Enhanced receipts table
        if (!Schema::hasTable('pos_receipts')) {
            Schema::create('pos_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_number')->unique();
                $table->foreignId('payment_id')->constrained('pos_payments');
                $table->decimal('total_amount', 15, 2);
                $table->dateTime('receipt_date');
                $table->json('bills_data'); // Store bill details for the receipt
                $table->text('receipt_content')->nullable(); // Formatted receipt content
                $table->enum('print_status', ['pending', 'printed', 'emailed'])->default('pending');
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
                
                $table->index('receipt_date');
                $table->index('print_status');
            });
        }

        // Invoice payments junction table
        if (!Schema::hasTable('invoice_payments')) {
            Schema::create('invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('finance_ar_invoices')->onDelete('cascade');
                $table->foreignId('payment_id')->constrained('pos_payments')->onDelete('cascade');
                $table->decimal('amount', 15, 2);
                $table->timestamps();
                
                $table->unique(['invoice_id', 'payment_id']);
                $table->index('payment_id');
            });
        }

        // Water bill payments junction table
        if (!Schema::hasTable('water_bill_payments')) {
            Schema::create('water_bill_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('water_bill_id')->constrained('water_bills')->onDelete('cascade');
                $table->foreignId('payment_id')->constrained('pos_payments')->onDelete('cascade');
                $table->decimal('amount', 15, 2);
                $table->timestamps();
                
                $table->unique(['water_bill_id', 'payment_id']);
                $table->index('payment_id');
            });
        }

        // Customer search index table for improved lookup performance
        if (!Schema::hasTable('customer_search_index')) {
            Schema::create('customer_search_index', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->text('search_terms'); // Concatenated searchable fields
                $table->string('customer_type')->index();
                $table->timestamps();
                
                // SQLite doesn't support fulltext, use regular index instead
                if (config('database.default') !== 'sqlite') {
                    $table->fullText('search_terms');
                } else {
                    $table->index('search_terms');
                }
                $table->unique('customer_id');
            });
        }

        // Payment gateway configurations
        if (!Schema::hasTable('payment_gateway_configs')) {
            Schema::create('payment_gateway_configs', function (Blueprint $table) {
                $table->id();
                $table->string('gateway_name'); // stripe, paypal, mobile_money, etc.
                $table->string('display_name');
                $table->boolean('is_active')->default(true);
                $table->json('configuration'); // Store API keys, settings, etc.
                $table->decimal('transaction_fee_percent', 5, 2)->default(0);
                $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->index(['is_active', 'sort_order']);
            });
        }

        // Loyalty program for customers
        if (!Schema::hasTable('customer_loyalty_points')) {
            Schema::create('customer_loyalty_points', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->integer('points_balance')->default(0);
                $table->integer('points_earned_total')->default(0);
                $table->integer('points_redeemed_total')->default(0);
                $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
                $table->date('tier_expiry_date')->nullable();
                $table->timestamps();
                
                $table->unique('customer_id');
                $table->index('tier');
            });
        }

        // Loyalty point transactions
        if (!Schema::hasTable('loyalty_point_transactions')) {
            Schema::create('loyalty_point_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->foreignId('payment_id')->nullable()->constrained('pos_payments')->onDelete('cascade');
                $table->enum('type', ['earned', 'redeemed', 'expired', 'adjustment']);
                $table->integer('points');
                $table->text('description');
                $table->date('expiry_date')->nullable();
                $table->timestamps();
                
                $table->index(['customer_id', 'type']);
                $table->index('expiry_date');
            });
        }

        // QR codes for bills
        if (!Schema::hasTable('bill_qr_codes')) {
            Schema::create('bill_qr_codes', function (Blueprint $table) {
                $table->id();
                $table->string('bill_type'); // invoice, water_bill, etc.
                $table->unsignedBigInteger('bill_id');
                $table->string('qr_code_data');
                $table->text('qr_code_svg')->nullable();
                $table->integer('scan_count')->default(0);
                $table->timestamp('last_scanned_at')->nullable();
                $table->timestamps();
                
                $table->unique(['bill_type', 'bill_id']);
                $table->index('qr_code_data');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_qr_codes');
        Schema::dropIfExists('loyalty_point_transactions');
        Schema::dropIfExists('customer_loyalty_points');
        Schema::dropIfExists('payment_gateway_configs');
        Schema::dropIfExists('customer_search_index');
        Schema::dropIfExists('water_bill_payments');
        Schema::dropIfExists('invoice_payments');
        Schema::dropIfExists('pos_receipts');
        Schema::dropIfExists('pos_payments');
    }
};