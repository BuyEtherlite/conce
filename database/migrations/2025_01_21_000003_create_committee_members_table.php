<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('committee_members')) {
            Schema::create('committee_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('committee_id')->constrained('committee_committees')->onDelete('cascade');
                $table->string('member_name');
                $table->string('member_type'); // chairman, secretary, member
                $table->string('position')->nullable();
                $table->date('appointment_date');
                $table->date('term_end_date')->nullable();
                $table->string('status')->default('active'); // active, inactive, resigned
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Table exists, ensure it has the correct structure
            Schema::table('committee_members', function (Blueprint $table) {
                if (!Schema::hasColumn('committee_members', 'member_name')) {
                    $table->string('member_name')->after('committee_id');
                }
                if (!Schema::hasColumn('committee_members', 'member_type')) {
                    $table->string('member_type')->after('member_name');
                }
                if (!Schema::hasColumn('committee_members', 'position')) {
                    $table->string('position')->nullable()->after('member_type');
                }
                if (!Schema::hasColumn('committee_members', 'appointment_date')) {
                    $table->date('appointment_date')->after('position');
                }
                if (!Schema::hasColumn('committee_members', 'term_end_date')) {
                    $table->date('term_end_date')->nullable()->after('appointment_date');
                }
                if (!Schema::hasColumn('committee_members', 'status')) {
                    $table->string('status')->default('active')->after('term_end_date');
                }
                if (!Schema::hasColumn('committee_members', 'notes')) {
                    $table->text('notes')->nullable()->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_members');
    }
};
