<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (Schema::hasTable('currencies')) {
            // Table exists, just ensure it has the required data
            $this->ensureDefaultCurrencies();
            return;
        }

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3)->unique(); // USD, EUR, ZWL, etc.
            $table->string('currency_name');
            $table->string('currency_symbol', 10);
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->boolean('is_base_currency')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('decimal_places')->default(2);
            $table->decimal('rounding_precision', 8, 6)->default(0.01);
            $table->timestamps();
        });

        $this->ensureDefaultCurrencies();
    }

    private function ensureDefaultCurrencies()
    {
        // Check if currencies already exist before inserting
        $existingCurrencies = DB::table('currencies')->pluck('currency_code')->toArray();
        
        $defaultCurrencies = [
            [
                'currency_code' => 'USD',
                'currency_name' => 'US Dollar',
                'currency_symbol' => '$',
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_base_currency' => true,
                'decimal_places' => 2,
                'rounding_precision' => 0.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'ZWL',
                'currency_name' => 'Zimbabwe Dollar',
                'currency_symbol' => 'Z$',
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_base_currency' => false,
                'decimal_places' => 2,
                'rounding_precision' => 0.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'currency_code' => 'ZAR',
                'currency_name' => 'South African Rand',
                'currency_symbol' => 'R',
                'exchange_rate' => 1.000000,
                'is_active' => true,
                'is_base_currency' => false,
                'decimal_places' => 2,
                'rounding_precision' => 0.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaultCurrencies as $currency) {
            if (!in_array($currency['currency_code'], $existingCurrencies)) {
                DB::table('currencies')->insert($currency);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
