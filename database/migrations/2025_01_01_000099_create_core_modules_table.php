<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('core_modules')) {
            Schema::create('core_modules', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('enabled')->default(true);
                $table->text('description')->nullable();
                $table->json('config')->nullable();
                $table->timestamps();
            });
        }

        // Insert default enabled modules if they don't exist
        $existingModules = DB::table('core_modules')->pluck('name')->toArray();
        
        $defaultModules = [
            ['name' => 'housing', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'administration', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'finance', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'committee', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($defaultModules as $module) {
            if (!in_array($module['name'], $existingModules)) {
                DB::table('core_modules')->insert($module);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_modules');
    }
};
