<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('councils', function (Blueprint $table) {
            if (!Schema::hasColumn('councils', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('councils', function (Blueprint $table) {
            if (Schema::hasColumn('councils', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
