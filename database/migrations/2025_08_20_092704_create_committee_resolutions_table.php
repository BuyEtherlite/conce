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
        Schema::create('committee_resolutions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('committee_id')->constrained('committees')->onDelete('cascade');
            $table->string('resolution_number', 50);
            $table->string('title');
            $table->text('description');
            $table->date('resolution_date');
            $table->string('proposed_by');
            $table->string('seconded_by')->nullable();
            $table->enum('status', ['proposed', 'approved', 'rejected', 'implemented'])->default('proposed');
            $table->text('implementation_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_resolutions');
    }
};
