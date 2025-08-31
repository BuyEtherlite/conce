<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('license_applications')) {
            Schema::create('license_applications', function (Blueprint $table) {
                $table->id();
                $table->string('application_number')->unique();
                $table->string('applicant_name');
                $table->string('business_name')->nullable();
                $table->string('license_type');
                $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
                $table->date('application_date');
                $table->date('review_date')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('fee_amount', 10, 2)->nullable();
                $table->boolean('fee_paid')->default(false);
                $table->timestamps();
            });
        } else {
            // Table exists, ensure it has the correct structure
            Schema::table('license_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('license_applications', 'application_number')) {
                    $table->string('application_number')->unique()->after('id');
                }
                if (!Schema::hasColumn('license_applications', 'applicant_name')) {
                    $table->string('applicant_name')->after('application_number');
                }
                if (!Schema::hasColumn('license_applications', 'business_name')) {
                    $table->string('business_name')->nullable()->after('applicant_name');
                }
                if (!Schema::hasColumn('license_applications', 'license_type')) {
                    $table->string('license_type')->after('business_name');
                }
                if (!Schema::hasColumn('license_applications', 'status')) {
                    $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending')->after('license_type');
                }
                if (!Schema::hasColumn('license_applications', 'application_date')) {
                    $table->date('application_date')->after('status');
                }
                if (!Schema::hasColumn('license_applications', 'review_date')) {
                    $table->date('review_date')->nullable()->after('application_date');
                }
                if (!Schema::hasColumn('license_applications', 'notes')) {
                    $table->text('notes')->nullable()->after('review_date');
                }
                if (!Schema::hasColumn('license_applications', 'fee_amount')) {
                    $table->decimal('fee_amount', 10, 2)->nullable()->after('notes');
                }
                if (!Schema::hasColumn('license_applications', 'fee_paid')) {
                    $table->boolean('fee_paid')->default(false)->after('fee_amount');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('license_applications');
    }
};
