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
        // Check if columns exist before trying to drop them
        if (Schema::hasColumn('finance_general_ledger', 'transaction_number') ||
            Schema::hasColumn('finance_general_ledger', 'account_id') ||
            Schema::hasColumn('finance_general_ledger', 'transaction_type') ||
            Schema::hasColumn('finance_general_ledger', 'amount') ||
            Schema::hasColumn('finance_general_ledger', 'source_document')) {
            
            Schema::table('finance_general_ledger', function (Blueprint $table) {
                // Drop foreign key first if it exists
                if (Schema::hasColumn('finance_general_ledger', 'account_id')) {
                    try {
                        $table->dropForeign(['account_id']);
                    } catch (Exception $e) {
                        // Foreign key may not exist, continue
                    }
                }
                
                // Drop columns that exist
                $columsToDrop = [];
                if (Schema::hasColumn('finance_general_ledger', 'transaction_number')) {
                    $columsToDrop[] = 'transaction_number';
                }
                if (Schema::hasColumn('finance_general_ledger', 'account_id')) {
                    $columsToDrop[] = 'account_id';
                }
                if (Schema::hasColumn('finance_general_ledger', 'transaction_type')) {
                    $columsToDrop[] = 'transaction_type';
                }
                if (Schema::hasColumn('finance_general_ledger', 'amount')) {
                    $columsToDrop[] = 'amount';
                }
                if (Schema::hasColumn('finance_general_ledger', 'source_document')) {
                    $columsToDrop[] = 'source_document';
                }
                
                if (!empty($columsToDrop)) {
                    $table->dropColumn($columsToDrop);
                }
            });
        }

        Schema::table('finance_general_ledger', function (Blueprint $table) {

            // Add the new columns if they don't exist
            if (!Schema::hasColumn('finance_general_ledger', 'transaction_id')) {
                $table->string('transaction_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'account_code')) {
                $table->string('account_code')->after('transaction_id');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'debit_amount')) {
                $table->decimal('debit_amount', 15, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'credit_amount')) {
                $table->decimal('credit_amount', 15, 2)->default(0)->after('debit_amount');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('credit_amount');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'source_module')) {
                $table->string('source_module')->nullable()->after('reference_number');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'source_document_type')) {
                $table->string('source_document_type')->nullable()->after('source_module');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'source_document_id')) {
                $table->unsignedBigInteger('source_document_id')->nullable()->after('source_document_type');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'program_code')) {
                $table->string('program_code')->nullable()->after('source_document_id');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'status')) {
                $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft')->after('program_code');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('finance_general_ledger', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        // Add foreign key constraints if they don't exist
        if (Schema::hasColumn('finance_general_ledger', 'account_code') && 
            !$this->hasForeignKey('finance_general_ledger', 'finance_general_ledger_account_code_foreign')) {
            Schema::table('finance_general_ledger', function (Blueprint $table) {
                $table->foreign('account_code')->references('account_code')->on('finance_chart_of_accounts');
            });
        }
        if (Schema::hasColumn('finance_general_ledger', 'approved_by') && 
            !$this->hasForeignKey('finance_general_ledger', 'finance_general_ledger_approved_by_foreign')) {
            Schema::table('finance_general_ledger', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users');
            });
        }

        // Add indexes only if they don't exist
        if (!$this->hasIndex('finance_general_ledger', 'finance_general_ledger_transaction_date_status_index')) {
            DB::statement('CREATE INDEX finance_general_ledger_transaction_date_status_index ON finance_general_ledger (transaction_date, status)');
        }
        if (!$this->hasIndex('finance_general_ledger', 'finance_general_ledger_account_code_status_index')) {
            DB::statement('CREATE INDEX finance_general_ledger_account_code_status_index ON finance_general_ledger (account_code, status)');
        }
        if (!$this->hasIndex('finance_general_ledger', 'finance_general_ledger_transaction_id_index')) {
            DB::statement('CREATE INDEX finance_general_ledger_transaction_id_index ON finance_general_ledger (transaction_id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_general_ledger', function (Blueprint $table) {
            // Drop foreign keys first
            if ($this->hasForeignKey('finance_general_ledger', 'finance_general_ledger_account_code_foreign')) {
                $table->dropForeign(['account_code']);
            }
            if ($this->hasForeignKey('finance_general_ledger', 'finance_general_ledger_approved_by_foreign')) {
                $table->dropForeign(['approved_by']);
            }

            // Drop new columns
            $table->dropColumn([
                'transaction_id',
                'account_code', 
                'debit_amount',
                'credit_amount',
                'reference_number',
                'source_module',
                'source_document_type', 
                'source_document_id',
                'program_code',
                'status',
                'approved_by',
                'approved_at'
            ]);

            // Restore old columns
            $table->string('transaction_number')->unique();
            $table->unsignedBigInteger('account_id');
            $table->string('transaction_type');
            $table->decimal('amount', 15, 2);
            $table->string('source_document')->nullable();

            // Restore old foreign key
            $table->foreign('account_id')->references('id')->on('finance_chart_of_accounts')->onDelete('cascade');
        });
    }

    private function hasForeignKey($table, $foreignKey)
    {
        try {
            $foreignKeys = DB::select("
                SELECT constraint_name 
                FROM information_schema.table_constraints 
                WHERE table_name = ? 
                AND constraint_type = 'FOREIGN KEY' 
                AND constraint_name = ?
            ", [$table, $foreignKey]);

            return count($foreignKeys) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    private function hasIndex($table, $indexName)
    {
        try {
            $indexes = DB::select("
                SELECT indexname 
                FROM pg_indexes 
                WHERE tablename = ? 
                AND indexname = ?
            ", [$table, $indexName]);

            return count($indexes) > 0;
        } catch (Exception $e) {
            // For SQLite or other databases, try a different approach
            try {
                $indexes = DB::select("PRAGMA index_list($table)");
                foreach ($indexes as $index) {
                    if ($index->name === $indexName) {
                        return true;
                    }
                }
                return false;
            } catch (Exception $e2) {
                return false;
            }
        }
    }
};
