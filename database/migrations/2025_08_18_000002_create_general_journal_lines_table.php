<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_general_journal_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_header_id');
            $table->string('account_code');
            $table->text('description');
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->integer('line_number');
            $table->timestamps();

            $table->foreign('journal_header_id')->references('id')->on('finance_general_journal_headers')->onDelete('cascade');
            $table->foreign('account_code')->references('account_code')->on('finance_chart_of_accounts');
            
            $table->index(['journal_header_id', 'line_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_general_journal_lines');
    }
};
