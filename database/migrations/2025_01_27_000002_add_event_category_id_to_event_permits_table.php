<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_permits', function (Blueprint $table) {
            $table->unsignedBigInteger('event_category_id')->nullable()->after('id');
            $table->foreign('event_category_id')->references('id')->on('event_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('event_permits', function (Blueprint $table) {
            $table->dropForeign(['event_category_id']);
            $table->dropColumn('event_category_id');
        });
    }
};
