<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('module_features')) {
            Schema::create('module_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('core_module_id')->constrained('core_modules')->onDelete('cascade');
            $table->string('module_name');
            $table->string('feature_name');
            $table->string('feature_key');
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->json('permissions')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['core_module_id', 'feature_key']);
            $table->unique(['core_module_id', 'feature_key']);
        });
        }
    }

    public function down()
    {
        Schema::dropIfExists('module_features');
    }
};
