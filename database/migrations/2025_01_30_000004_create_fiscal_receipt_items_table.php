<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fiscal_receipt_id');
            $table->integer('line_number');
            $table->enum('line_type', ['Sale', 'Discount'])->default('Sale');
            $table->string('hs_code', 8)->nullable(); // Harmonized System code
            $table->string('item_code', 50)->nullable();
            $table->string('item_name');
            $table->decimal('quantity', 25, 6);
            $table->decimal('unit_price', 25, 6)->nullable();
            $table->decimal('line_total', 21, 2);
            $table->string('tax_code', 3)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->integer('tax_id');
            $table->decimal('tax_amount', 21, 2);
            $table->string('tax_category', 50)->nullable();
            $table->timestamps();

            $table->foreign('fiscal_receipt_id')->references('id')->on('fiscal_receipts');
            $table->index(['fiscal_receipt_id', 'line_number']);
            $table->index('tax_id');
            $table->index('hs_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fiscal_receipt_items');
    }
};