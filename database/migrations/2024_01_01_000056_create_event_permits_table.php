<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();
            $table->string('event_name');
            $table->string('organizer_name');
            $table->string('organizer_contact');
            $table->string('event_type'); // concert, festival, market, sports, religious
            $table->text('event_description');
            $table->text('venue_location');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('expected_attendance');
            $table->decimal('permit_fee', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected, expired
            $table->text('conditions')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('application_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_permits');
    }
};
