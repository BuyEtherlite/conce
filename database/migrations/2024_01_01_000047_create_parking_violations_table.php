<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_violations', function (Blueprint $table) {
            $table->id();
            $table->string('violation_number')->unique();
            $table->string('vehicle_registration');
            $table->foreignId('zone_id')->constrained('parking_zones')->onDelete('cascade');
            $table->string('violation_type'); // expired_meter, no_permit, restricted_area
            $table->datetime('violation_datetime');
            $table->string('officer_name');
            $table->decimal('fine_amount', 8, 2);
            $table->string('status')->default('unpaid'); // unpaid, paid, disputed, cancelled
            $table->date('due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_violations');
    }
};
