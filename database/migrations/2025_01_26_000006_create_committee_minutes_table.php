<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('committee_minutes')) {
            Schema::create('committee_minutes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('meeting_id')->constrained('committee_meetings');
            $table->foreignId('agenda_item_id')->nullable()->constrained('committee_agendas');
            $table->string('minute_type'); // opening, agenda_item, motion, resolution, adjournment
            $table->text('content');
            $table->string('motion_type')->nullable(); // ordinary, special, procedural
            $table->string('moved_by')->nullable();
            $table->string('seconded_by')->nullable();
            $table->string('voting_result')->nullable(); // carried, defeated, unanimous
            $table->json('voting_details')->nullable(); // for, against, abstain counts
            $table->text('discussion_summary')->nullable();
            $table->text('decision')->nullable();
            $table->text('action_items')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('draft'); // draft, approved, published
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_minutes');
    }
};
