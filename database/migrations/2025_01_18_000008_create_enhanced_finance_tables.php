<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create currencies table first
        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 3)->unique(); // USD, EUR, ZWL, etc.
                $table->string('name');
                $table->string('symbol', 10);
                $table->decimal('exchange_rate', 12, 6)->default(1.000000);
                $table->boolean('is_base')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create Zimbabwe Chart of Accounts
        if (!Schema::hasTable('zimbabwe_chart_of_accounts')) {
            Schema::create('zimbabwe_chart_of_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('account_code', 20)->unique();
                $table->string('account_name');
                $table->string('account_type'); // asset, liability, equity, revenue, expense
                $table->string('account_category'); // current_asset, non_current_asset, etc.
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create FDMS Receipt Settings
        if (!Schema::hasTable('fdms_settings')) {
            Schema::create('fdms_settings', function (Blueprint $table) {
                $table->id();
                $table->string('operator_id');
                $table->string('terminal_id');
                $table->string('certificate_path');
                $table->string('api_endpoint');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fdms_settings');
        Schema::dropIfExists('zimbabwe_chart_of_accounts');
        Schema::dropIfExists('currencies');
    }
};