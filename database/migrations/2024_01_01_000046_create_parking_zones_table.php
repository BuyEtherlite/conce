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
        if (!Schema::hasTable('parking_zones')) {
            Schema::create('parking_zones', function (Blueprint $table) {
                $table->id();
                $table->string('zone_name');
                $table->string('zone_code')->unique();
                $table->text('description')->nullable();
                $table->decimal('hourly_rate', 8, 2);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Table exists, ensure it has the correct structure
            Schema::table('parking_zones', function (Blueprint $table) {
                if (!Schema::hasColumn('parking_zones', 'zone_name')) {
                    $table->string('zone_name')->after('id');
                }
                if (!Schema::hasColumn('parking_zones', 'zone_code')) {
                    $table->string('zone_code')->unique()->after('zone_name');
                }
                if (!Schema::hasColumn('parking_zones', 'description')) {
                    $table->text('description')->nullable()->after('zone_code');
                }
                if (!Schema::hasColumn('parking_zones', 'hourly_rate')) {
                    $table->decimal('hourly_rate', 8, 2)->after('description');
                }
                if (!Schema::hasColumn('parking_zones', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('hourly_rate');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_zones');
    }
};