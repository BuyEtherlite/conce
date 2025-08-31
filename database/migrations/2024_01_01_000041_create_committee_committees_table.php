<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_committees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // standing, ad_hoc, subcommittee
            $table->date('established_date');
            $table->date('dissolution_date')->nullable();
            $table->string('chairperson');
            $table->string('secretary');
            $table->json('meeting_schedule')->nullable();
            $table->string('status')->default('active'); // active, inactive, dissolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_committees');
    }
};
