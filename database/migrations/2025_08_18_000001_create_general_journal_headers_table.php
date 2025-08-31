<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_general_journal_headers', function (Blueprint $table) {
            $table->id();
            $table->string('journal_number')->unique();
            $table->date('journal_date');
            $table->string('reference')->nullable();
            $table->text('description');
            $table->enum('journal_type', ['general', 'recurring', 'reversing', 'closing'])->default('general');
            $table->enum('status', ['draft', 'approved', 'posted'])->default('draft');
            $table->decimal('total_debits', 15, 2)->default(0);
            $table->decimal('total_credits', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('posted_by')->references('id')->on('users');
            
            $table->index(['journal_date', 'status']);
            $table->index('journal_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_general_journal_headers');
    }
};
