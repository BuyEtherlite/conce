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
        if (!Schema::hasTable('fiscal_devices')) {
            Schema::create('fiscal_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 50)->unique();
            $table->string('device_name');
            $table->string('serial_number', 100)->unique();
            $table->string('manufacturer', 100);
            $table->string('model', 100);
            $table->string('firmware_version', 50)->nullable();
            $table->string('certification_number', 100);
            $table->string('zimra_registration_number')->nullable();
            $table->date('installation_date');
            $table->date('last_maintenance_date')->nullable();
            $table->timestamp('zimra_registered_at')->nullable();
            $table->date('last_z_report_date')->nullable();
            $table->integer('fiscal_day_number')->default(1);
            $table->enum('status', ['active', 'inactive', 'maintenance', 'error'])->default('active');
            $table->string('location');
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->string('tax_office_code', 10)->nullable();
            $table->enum('device_type', ['pos_terminal', 'cashier_terminal', 'mobile_device']);
            $table->json('configuration')->nullable();
            $table->unsignedBigInteger('last_receipt_number')->default(0);
            $table->unsignedBigInteger('total_receipts_issued')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('assigned_user_id')->references('id')->on('users');
            $table->index(['is_active', 'status']);
            $table->index('device_type');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_devices');
    }
};