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
        // Update all super_admin roles to admin
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'admin']);

        // Remove is_super_admin column if it exists
        if (Schema::hasColumn('users', 'is_super_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_super_admin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function reverse(): void
    {
        // Add back is_super_admin column
        if (!Schema::hasColumn('users', 'is_super_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_super_admin')->default(false)->after('active');
            });
        }
    }
};
