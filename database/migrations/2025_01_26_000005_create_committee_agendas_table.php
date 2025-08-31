<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_agendas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('meeting_id')->constrained('committee_meetings');
            $table->integer('agenda_order');
            $table->string('item_title');
            $table->text('item_description');
            $table->string('item_type'); // discussion, decision, information, presentation
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->string('presenter')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->text('background_info')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('status')->default('pending'); // pending, discussed, deferred, resolved
            $table->text('outcome')->nullable();
            $table->text('action_required')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('deadline')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_agendas');
    }
};
