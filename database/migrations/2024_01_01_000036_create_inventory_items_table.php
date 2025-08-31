<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inventory_items')) {
            Schema::create('inventory_items', function (Blueprint $table) {
                $table->id();
                $table->string('item_code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->constrained('inventory_categories')->onDelete('cascade');
                $table->string('unit_of_measure');
                $table->decimal('unit_cost', 10, 2);
                $table->integer('current_stock')->default(0);
                $table->integer('minimum_stock_level')->default(0);
                $table->integer('maximum_stock_level')->default(0);
                $table->string('location')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Table exists, ensure it has the correct structure
            Schema::table('inventory_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_items', 'item_code')) {
                    $table->string('item_code')->unique()->after('id');
                }
                if (!Schema::hasColumn('inventory_items', 'name')) {
                    $table->string('name')->after('item_code');
                }
                if (!Schema::hasColumn('inventory_items', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                if (!Schema::hasColumn('inventory_items', 'category_id')) {
                    $table->foreignId('category_id')->constrained('inventory_categories')->onDelete('cascade')->after('description');
                }
                if (!Schema::hasColumn('inventory_items', 'unit_of_measure')) {
                    $table->string('unit_of_measure')->after('category_id');
                }
                if (!Schema::hasColumn('inventory_items', 'unit_cost')) {
                    $table->decimal('unit_cost', 10, 2)->after('unit_of_measure');
                }
                if (!Schema::hasColumn('inventory_items', 'current_stock')) {
                    $table->integer('current_stock')->default(0)->after('unit_cost');
                }
                if (!Schema::hasColumn('inventory_items', 'minimum_stock_level')) {
                    $table->integer('minimum_stock_level')->default(0)->after('current_stock');
                }
                if (!Schema::hasColumn('inventory_items', 'maximum_stock_level')) {
                    $table->integer('maximum_stock_level')->default(0)->after('minimum_stock_level');
                }
                if (!Schema::hasColumn('inventory_items', 'location')) {
                    $table->string('location')->nullable()->after('maximum_stock_level');
                }
                if (!Schema::hasColumn('inventory_items', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('location');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
