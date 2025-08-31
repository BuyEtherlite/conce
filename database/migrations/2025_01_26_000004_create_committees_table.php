<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('council_id')->constrained('councils');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // standing, special, ad-hoc
            $table->date('established_date');
            $table->date('term_start_date');
            $table->date('term_end_date')->nullable();
            $table->string('meeting_frequency'); // weekly, monthly, quarterly, as_needed
            $table->string('meeting_venue')->nullable();
            $table->time('meeting_time')->nullable();
            $table->string('chairperson')->nullable();
            $table->string('secretary')->nullable();
            $table->string('status')->default('active'); // active, inactive, dissolved
            $table->text('mandate')->nullable();
            $table->json('responsibilities')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
