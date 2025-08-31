<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('core_module_id')->constrained('core_modules')->onDelete('cascade');
            $table->string('module_name');
            $table->string('feature_name');
            $table->string('feature_key')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->json('permissions')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['core_module_id', 'is_enabled']);
            $table->index('module_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_features');
    }
};
