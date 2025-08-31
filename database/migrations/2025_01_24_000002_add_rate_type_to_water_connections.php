<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('water_connections', function (Blueprint $table) {
            $table->enum('rate_type', ['standard', 'commercial', 'industrial', 'domestic'])->default('standard')->after('status');
        });
    }

    public function down()
    {
        Schema::table('water_connections', function (Blueprint $table) {
            $table->dropColumn('rate_type');
        });
    }
};
