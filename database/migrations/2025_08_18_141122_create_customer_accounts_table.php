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
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->enum('account_type', ['individual', 'business', 'organization']);
            $table->string('customer_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('physical_address');
            $table->text('postal_address')->nullable();
            $table->string('id_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('council_id')->constrained('councils')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['account_number']);
            $table->index(['customer_name']);
            $table->index(['is_active']);
            $table->index(['council_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_accounts');
    }
};
