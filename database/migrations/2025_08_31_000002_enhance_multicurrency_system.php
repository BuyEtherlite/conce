<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance currencies table
        if (Schema::hasTable('currencies')) {
            Schema::table('currencies', function (Blueprint $table) {
                if (!Schema::hasColumn('currencies', 'exchange_rate')) {
                    $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('symbol');
                }
                if (!Schema::hasColumn('currencies', 'auto_update')) {
                    $table->boolean('auto_update')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('currencies', 'rounding_precision')) {
                    $table->integer('rounding_precision')->default(2)->after('auto_update');
                }
                if (!Schema::hasColumn('currencies', 'last_updated')) {
                    $table->timestamp('last_updated')->nullable()->after('rounding_precision');
                }
            });
        }

        // Create exchange rate history table
        if (!Schema::hasTable('exchange_rate_histories')) {
            Schema::create('exchange_rate_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
                $table->decimal('exchange_rate', 15, 6);
                $table->datetime('effective_date');
                $table->enum('source', ['manual', 'api', 'import', 'system'])->default('manual');
                $table->boolean('is_active')->default(true);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->timestamps();
                
                $table->index(['currency_id', 'effective_date']);
                $table->index(['effective_date', 'is_active']);
                $table->index('source');
            });
        }

        // Create currency pairs table for cross-rates
        if (!Schema::hasTable('currency_pairs')) {
            Schema::create('currency_pairs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('base_currency_id')->constrained('currencies');
                $table->foreignId('quote_currency_id')->constrained('currencies');
                $table->decimal('rate', 15, 6);
                $table->decimal('spread', 8, 4)->default(0); // Bid-ask spread
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_updated');
                $table->timestamps();
                
                $table->unique(['base_currency_id', 'quote_currency_id']);
                $table->index('is_active');
                $table->index('last_updated');
            });
        }

        // Create currency conversion logs table
        if (!Schema::hasTable('currency_conversion_logs')) {
            Schema::create('currency_conversion_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('from_currency_id')->constrained('currencies');
                $table->foreignId('to_currency_id')->constrained('currencies');
                $table->decimal('original_amount', 15, 2);
                $table->decimal('converted_amount', 15, 2);
                $table->decimal('exchange_rate_used', 15, 6);
                $table->string('transaction_type')->nullable(); // invoice, payment, etc
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->string('session_id', 50)->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->timestamps();
                
                $table->index(['from_currency_id', 'to_currency_id']);
                $table->index(['transaction_type', 'transaction_id']);
                $table->index('session_id');
                $table->index('created_at');
            });
        }

        // Create currency exposure tracking table
        if (!Schema::hasTable('currency_exposures')) {
            Schema::create('currency_exposures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('currency_id')->constrained('currencies');
                $table->string('account_type'); // receivables, payables, cash, etc
                $table->decimal('exposure_amount', 15, 2);
                $table->decimal('hedged_amount', 15, 2)->default(0);
                $table->decimal('net_exposure', 15, 2);
                $table->date('exposure_date');
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index(['currency_id', 'exposure_date']);
                $table->index(['account_type', 'exposure_date']);
            });
        }

        // Create automated exchange rate sources configuration
        if (!Schema::hasTable('exchange_rate_sources')) {
            Schema::create('exchange_rate_sources', function (Blueprint $table) {
                $table->id();
                $table->string('source_name'); // API provider name
                $table->string('source_url');
                $table->json('api_config'); // API keys, endpoints, etc
                $table->boolean('is_active')->default(true);
                $table->integer('update_frequency_minutes')->default(60);
                $table->timestamp('last_update_attempt')->nullable();
                $table->timestamp('last_successful_update')->nullable();
                $table->text('last_error')->nullable();
                $table->integer('priority')->default(1);
                $table->timestamps();
                
                $table->index(['is_active', 'priority']);
                $table->index('last_successful_update');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rate_sources');
        Schema::dropIfExists('currency_exposures');
        Schema::dropIfExists('currency_conversion_logs');
        Schema::dropIfExists('currency_pairs');
        Schema::dropIfExists('exchange_rate_histories');
        
        if (Schema::hasTable('currencies')) {
            Schema::table('currencies', function (Blueprint $table) {
                $table->dropColumn(['exchange_rate', 'auto_update', 'rounding_precision', 'last_updated']);
            });
        }
    }
};