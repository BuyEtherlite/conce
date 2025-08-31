<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenue_collections', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('revenue_source'); // rates, fines, licenses, permits, services
            $table->string('source_reference')->nullable(); // invoice number, permit number, etc.
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('collection_date');
            $table->decimal('amount_collected', 15, 2);
            $table->string('payment_method'); // cash, card, eft, cheque
            $table->string('payment_reference')->nullable();
            $table->foreignId('collected_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_collections');
    }
};
