<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if table exists
        if (!Schema::hasTable('water_bills')) {
            // Create the table if it doesn't exist
            Schema::create('water_bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_number')->unique();
                $table->foreignId('connection_id')->constrained('water_connections')->onDelete('cascade');
                $table->date('bill_date');
                $table->date('due_date');
                $table->string('billing_period');
                $table->decimal('consumption', 10, 2);
                $table->decimal('basic_charge', 10, 2);
                $table->decimal('consumption_charge', 10, 2);
                $table->decimal('vat_amount', 10, 2);
                $table->decimal('total_amount', 10, 2);
                $table->decimal('amount_paid', 10, 2)->default(0);
                $table->decimal('balance', 10, 2);
                $table->string('status')->default('unpaid'); // unpaid, paid, overdue
                $table->timestamps();
            });
        } else {
            // Table exists, check for missing columns and add them
            Schema::table('water_bills', function (Blueprint $table) {
                if (!Schema::hasColumn('water_bills', 'id')) {
                    $table->id();
                }
                if (!Schema::hasColumn('water_bills', 'bill_number')) {
                    $table->string('bill_number')->unique();
                }
                if (!Schema::hasColumn('water_bills', 'connection_id')) {
                    $table->foreignId('connection_id')->constrained('water_connections')->onDelete('cascade');
                }
                if (!Schema::hasColumn('water_bills', 'bill_date')) {
                    $table->date('bill_date');
                }
                if (!Schema::hasColumn('water_bills', 'due_date')) {
                    $table->date('due_date');
                }
                if (!Schema::hasColumn('water_bills', 'billing_period')) {
                    $table->string('billing_period');
                }
                if (!Schema::hasColumn('water_bills', 'consumption')) {
                    $table->decimal('consumption', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'basic_charge')) {
                    $table->decimal('basic_charge', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'consumption_charge')) {
                    $table->decimal('consumption_charge', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'vat_amount')) {
                    $table->decimal('vat_amount', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'amount_paid')) {
                    $table->decimal('amount_paid', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('water_bills', 'balance')) {
                    $table->decimal('balance', 10, 2);
                }
                if (!Schema::hasColumn('water_bills', 'status')) {
                    $table->string('status')->default('unpaid');
                }
                if (!Schema::hasColumn('water_bills', 'created_at')) {
                    $table->timestamps();
                }
            });

            // Add unique constraint for bill_number if it doesn't exist
            try {
                DB::statement('ALTER TABLE water_bills ADD CONSTRAINT water_bills_bill_number_unique UNIQUE (bill_number)');
            } catch (\Exception $e) {
                // Constraint probably already exists, ignore
            }

            // Add foreign key constraint if it doesn't exist
            try {
                DB::statement('ALTER TABLE water_bills ADD CONSTRAINT water_bills_connection_id_foreign FOREIGN KEY (connection_id) REFERENCES water_connections(id) ON DELETE CASCADE');
            } catch (\Exception $e) {
                // Constraint probably already exists, ignore
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('water_bills');
    }
};
