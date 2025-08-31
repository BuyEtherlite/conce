<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DatabaseFailoverService
{
    protected $primaryConnection = 'mysql';
    protected $backupConnection = 'mysql_backup';
    protected $maxRetries = 3;
    protected $retryDelay = 2; // seconds

    public function executeWithFailover(callable $callback, array $params = [])
    {
        return $this->attemptOperation($callback, $params, $this->primaryConnection);
    }

    private function attemptOperation(callable $callback, array $params, string $connection)
    {
        try {
            return DB::connection($connection)->transaction(function () use ($callback, $params) {
                return call_user_func_array($callback, $params);
            });
        } catch (Exception $e) {
            Log::error("Database operation failed on {$connection}: " . $e->getMessage());
            
            if ($connection === $this->primaryConnection) {
                Log::info("Attempting failover to backup database");
                return $this->attemptOperation($callback, $params, $this->backupConnection);
            }
            
            throw $e;
        }
    }

    public function syncToBackup()
    {
        try {
            $tables = $this->getAllTables();
            
            foreach ($tables as $table) {
                $this->syncTable($table);
            }
            
            Log::info("Database sync to backup completed successfully");
            return true;
        } catch (Exception $e) {
            Log::error("Database sync failed: " . $e->getMessage());
            return false;
        }
    }

    private function getAllTables()
    {
        return DB::connection($this->primaryConnection)
            ->select("SHOW TABLES")
            ->map(function ($table) {
                return array_values((array) $table)[0];
            })
            ->toArray();
    }

    private function syncTable(string $tableName)
    {
        try {
            // Get primary data
            $primaryData = DB::connection($this->primaryConnection)
                ->table($tableName)
                ->where('updated_at', '>=', now()->subHours(24))
                ->get();

            if ($primaryData->isEmpty()) {
                return;
            }

            // Clear old data from backup
            DB::connection($this->backupConnection)
                ->table($tableName)
                ->where('updated_at', '>=', now()->subHours(24))
                ->delete();

            // Insert updated data to backup
            $chunks = $primaryData->chunk(1000);
            foreach ($chunks as $chunk) {
                DB::connection($this->backupConnection)
                    ->table($tableName)
                    ->insert($chunk->toArray());
            }

            Log::debug("Synced table: {$tableName}");
        } catch (Exception $e) {
            Log::error("Failed to sync table {$tableName}: " . $e->getMessage());
            throw $e;
        }
    }

    public function checkConnectionHealth(string $connection = null)
    {
        $connection = $connection ?: $this->primaryConnection;
        
        try {
            DB::connection($connection)->getPdo();
            return true;
        } catch (Exception $e) {
            Log::warning("Database connection {$connection} health check failed: " . $e->getMessage());
            return false;
        }
    }
}
