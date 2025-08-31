<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraints that reference currencies table
        if (Schema::hasTable('cashbook_entries')) {
            Schema::table('cashbook_entries', function (Blueprint $table) {
                if (Schema::hasColumn('cashbook_entries', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        if (Schema::hasTable('debtor_transactions')) {
            Schema::table('debtor_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('debtor_transactions', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        if (Schema::hasTable('exchange_rate_histories')) {
            Schema::table('exchange_rate_histories', function (Blueprint $table) {
                if (Schema::hasColumn('exchange_rate_histories', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        if (Schema::hasTable('fdms_receipts')) {
            Schema::table('fdms_receipts', function (Blueprint $table) {
                if (Schema::hasColumn('fdms_receipts', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        if (Schema::hasTable('fixed_assets')) {
            Schema::table('fixed_assets', function (Blueprint $table) {
                if (Schema::hasColumn('fixed_assets', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                if (Schema::hasColumn('vouchers', 'currency_code')) {
                    $table->dropForeign(['currency_code']);
                }
            });
        }

        // Drop currency_rates table first since it depends on currencies
        Schema::dropIfExists('currency_rates');
        
        // Now safely drop and recreate currencies table
        Schema::dropIfExists('currencies');
        
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('symbol', 10);
            $table->decimal('exchange_rate', 15, 6)->default(1.000000);
            $table->boolean('is_base')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default currencies
        DB::table('currencies')->insert([
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.000000,
                'is_base' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ZWL',
                'name' => 'Zimbabwe Dollar',
                'symbol' => 'Z$',
                'exchange_rate' => 1.000000,
                'is_base' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Recreate currency_rates table with proper foreign key
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('exchange_rate', 15, 6);
            $table->date('effective_date');
            $table->enum('rate_type', ['buy', 'sell', 'mid'])->default('mid');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['currency_id', 'effective_date']);
            $table->index(['effective_date', 'is_active']);
        });

        // Re-add foreign key constraints with proper references
        if (Schema::hasTable('cashbook_entries') && Schema::hasColumn('cashbook_entries', 'currency_code')) {
            Schema::table('cashbook_entries', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }

        if (Schema::hasTable('debtor_transactions') && Schema::hasColumn('debtor_transactions', 'currency_code')) {
            Schema::table('debtor_transactions', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }

        if (Schema::hasTable('exchange_rate_histories') && Schema::hasColumn('exchange_rate_histories', 'currency_code')) {
            Schema::table('exchange_rate_histories', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }

        if (Schema::hasTable('fdms_receipts') && Schema::hasColumn('fdms_receipts', 'currency_code')) {
            Schema::table('fdms_receipts', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }

        if (Schema::hasTable('fixed_assets') && Schema::hasColumn('fixed_assets', 'currency_code')) {
            Schema::table('fixed_assets', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }

        if (Schema::hasTable('vouchers') && Schema::hasColumn('vouchers', 'currency_code')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
