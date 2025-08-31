<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fiscal_configurations', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_fiscalization_enabled')->default(false);
            $table->string('company_tin', 20)->nullable();
            $table->string('company_name');
            $table->text('company_address');
            $table->string('tax_office_code', 10)->nullable();
            $table->string('business_license_number', 50)->nullable();
            $table->string('vat_registration_number', 50)->nullable();
            $table->date('fiscal_year_start');
            $table->decimal('default_tax_rate', 5, 2)->default(15.00);
            $table->string('currency_code', 3)->default('USD');
            $table->text('receipt_header_text')->nullable();
            $table->text('receipt_footer_text')->nullable();
            $table->boolean('require_customer_details')->default(false);
            $table->boolean('auto_transmit_to_zimra')->default(true);
            $table->integer('backup_frequency')->default(24); // hours
            $table->json('configuration_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiscal_configurations');
    }
};
