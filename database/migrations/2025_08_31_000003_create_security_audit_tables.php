<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Finance audit logs table
        if (!Schema::hasTable('finance_audit_logs')) {
            Schema::create('finance_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->ipAddress('ip_address');
                $table->text('user_agent')->nullable();
                $table->string('request_method', 10);
                $table->text('request_url');
                $table->string('request_path');
                $table->json('request_data')->nullable();
                $table->integer('response_status');
                $table->json('response_data')->nullable();
                $table->decimal('duration_ms', 10, 2);
                $table->string('module', 50);
                $table->string('action', 100);
                $table->string('session_id', 255)->nullable();
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                
                $table->index(['user_id', 'created_at']);
                $table->index(['module', 'action']);
                $table->index(['created_at']);
                $table->index(['ip_address', 'created_at']);
                $table->index('session_id');
            });
        }

        // API access logs table for rate limiting and monitoring
        if (!Schema::hasTable('api_access_logs')) {
            Schema::create('api_access_logs', function (Blueprint $table) {
                $table->id();
                $table->ipAddress('ip_address');
                $table->string('endpoint');
                $table->string('method', 10);
                $table->integer('response_status');
                $table->decimal('response_time_ms', 10, 2);
                $table->string('user_agent')->nullable();
                $table->string('api_key_hash', 64)->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('created_at');
                
                $table->index(['ip_address', 'created_at']);
                $table->index(['endpoint', 'created_at']);
                $table->index(['api_key_hash', 'created_at']);
                $table->index('created_at');
            });
        }

        // Security incidents table
        if (!Schema::hasTable('security_incidents')) {
            Schema::create('security_incidents', function (Blueprint $table) {
                $table->id();
                $table->enum('incident_type', [
                    'rate_limit_exceeded',
                    'invalid_api_key',
                    'suspicious_activity',
                    'unauthorized_access',
                    'data_breach_attempt',
                    'injection_attempt'
                ]);
                $table->ipAddress('ip_address');
                $table->text('description');
                $table->json('details')->nullable();
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['open', 'investigating', 'resolved', 'false_positive'])->default('open');
                $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('incident_time');
                $table->timestamp('resolved_at')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamps();
                
                $table->index(['incident_type', 'status']);
                $table->index(['severity', 'created_at']);
                $table->index(['ip_address', 'incident_time']);
                $table->index('status');
            });
        }

        // API rate limits configuration
        if (!Schema::hasTable('api_rate_limits')) {
            Schema::create('api_rate_limits', function (Blueprint $table) {
                $table->id();
                $table->string('endpoint_pattern');
                $table->string('method', 10)->default('*');
                $table->integer('requests_per_minute')->default(60);
                $table->integer('requests_per_hour')->default(1000);
                $table->integer('requests_per_day')->default(10000);
                $table->enum('user_type', ['guest', 'authenticated', 'premium', 'admin'])->default('guest');
                $table->boolean('is_active')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index(['endpoint_pattern', 'method']);
                $table->index(['user_type', 'is_active']);
            });
        }

        // Authentication attempts log
        if (!Schema::hasTable('authentication_logs')) {
            Schema::create('authentication_logs', function (Blueprint $table) {
                $table->id();
                $table->string('email')->nullable();
                $table->ipAddress('ip_address');
                $table->enum('attempt_type', ['login', 'logout', 'password_reset', 'api_auth']);
                $table->boolean('successful')->default(false);
                $table->string('failure_reason')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('session_id', 255)->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->timestamp('attempted_at');
                
                $table->index(['email', 'attempted_at']);
                $table->index(['ip_address', 'attempted_at']);
                $table->index(['successful', 'attempted_at']);
                $table->index('session_id');
            });
        }

        // Data access logs for sensitive operations
        if (!Schema::hasTable('data_access_logs')) {
            Schema::create('data_access_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('table_name');
                $table->string('record_id')->nullable();
                $table->enum('operation', ['select', 'insert', 'update', 'delete']);
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->ipAddress('ip_address');
                $table->string('session_id', 255)->nullable();
                $table->text('reason')->nullable();
                $table->timestamp('accessed_at');
                
                $table->index(['table_name', 'operation']);
                $table->index(['user_id', 'accessed_at']);
                $table->index(['record_id', 'table_name']);
                $table->index('accessed_at');
            });
        }

        // System performance monitoring
        if (!Schema::hasTable('performance_metrics')) {
            Schema::create('performance_metrics', function (Blueprint $table) {
                $table->id();
                $table->string('metric_type'); // cpu, memory, database, api_response_time
                $table->string('metric_name');
                $table->decimal('value', 15, 4);
                $table->string('unit')->nullable(); // %, ms, MB, etc.
                $table->json('metadata')->nullable();
                $table->timestamp('recorded_at');
                
                $table->index(['metric_type', 'recorded_at']);
                $table->index(['metric_name', 'recorded_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('data_access_logs');
        Schema::dropIfExists('authentication_logs');
        Schema::dropIfExists('api_rate_limits');
        Schema::dropIfExists('security_incidents');
        Schema::dropIfExists('api_access_logs');
        Schema::dropIfExists('finance_audit_logs');
    }
};