<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign key constraint first if module_features table exists
        if (Schema::hasTable('module_features')) {
            Schema::table('module_features', function (Blueprint $table) {
                $table->dropForeign(['core_module_id']);
            });
        }
        
        // Now safely drop the core_modules table
        Schema::dropIfExists('core_modules');
    }

    public function down(): void
    {
        Schema::create('core_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('icon')->default('cog');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_core')->default(false);
            $table->json('permissions')->nullable();
            $table->string('version')->default('1.0.0');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Recreate foreign key constraint
        if (Schema::hasTable('module_features')) {
            Schema::table('module_features', function (Blueprint $table) {
                $table->foreign('core_module_id')->references('id')->on('core_modules')->onDelete('cascade');
            });
        }
    }
};
