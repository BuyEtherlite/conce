<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FinanceAuditTrail
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Process the request
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds
        
        // Log financial transactions and sensitive operations
        if ($this->shouldAudit($request)) {
            $this->createAuditEntry($request, $response, $duration);
        }
        
        return $response;
    }

    /**
     * Determine if the request should be audited
     */
    private function shouldAudit(Request $request): bool
    {
        $auditablePaths = [
            'api/v1/finance/pos/process-payment',
            'api/v1/finance/multicurrency/convert',
            'api/v1/finance/multicurrency/update-rates',
            'finance/multicurrency',
            'finance/pos',
            'finance/ipsas'
        ];

        $path = $request->path();
        
        foreach ($auditablePaths as $auditablePath) {
            if (str_contains($path, $auditablePath)) {
                return true;
            }
        }

        // Audit all POST, PUT, DELETE requests to finance endpoints
        if (str_contains($path, 'finance') && in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            return true;
        }

        return false;
    }

    /**
     * Create audit trail entry
     */
    private function createAuditEntry(Request $request, Response $response, float $duration): void
    {
        try {
            $auditData = [
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl(),
                'request_path' => $request->path(),
                'request_data' => $this->sanitizeRequestData($request),
                'response_status' => $response->getStatusCode(),
                'response_data' => $this->sanitizeResponseData($response),
                'duration_ms' => $duration,
                'module' => $this->determineModule($request),
                'action' => $this->determineAction($request),
                'session_id' => $request->session()?->getId(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Insert into audit log table
            DB::table('finance_audit_logs')->insert($auditData);

            // Also log to Laravel log for immediate monitoring
            Log::channel('audit')->info('Finance Operation Audited', [
                'user_id' => Auth::id(),
                'action' => $auditData['action'],
                'module' => $auditData['module'],
                'status' => $response->getStatusCode(),
                'duration' => $duration . 'ms'
            ]);

        } catch (\Exception $e) {
            // Don't fail the request if audit logging fails
            Log::error('Audit logging failed', [
                'error' => $e->getMessage(),
                'request_path' => $request->path()
            ]);
        }
    }

    /**
     * Sanitize request data for logging (remove sensitive information)
     */
    private function sanitizeRequestData(Request $request): array
    {
        $data = $request->all();

        // Remove sensitive fields
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'api_key',
            'token',
            'credit_card_number',
            'cvv',
            'ssn'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        // Limit size to prevent huge logs
        $jsonData = json_encode($data);
        if (strlen($jsonData) > 5000) {
            return ['message' => 'Request data too large for audit log'];
        }

        return $data;
    }

    /**
     * Sanitize response data for logging
     */
    private function sanitizeResponseData(Response $response): ?array
    {
        $content = $response->getContent();
        
        // Only log JSON responses
        if (!$this->isJson($content)) {
            return null;
        }

        $data = json_decode($content, true);
        
        if (!is_array($data)) {
            return null;
        }

        // Remove sensitive response fields
        $sensitiveFields = [
            'api_key',
            'token',
            'password',
            'credit_card_number'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        // Limit response data size
        $jsonData = json_encode($data);
        if (strlen($jsonData) > 3000) {
            return [
                'message' => 'Response data too large for audit log',
                'success' => $data['success'] ?? null,
                'error' => $data['error'] ?? null
            ];
        }

        return $data;
    }

    /**
     * Determine the module from request path
     */
    private function determineModule(Request $request): string
    {
        $path = $request->path();

        if (str_contains($path, 'pos')) {
            return 'POS';
        } elseif (str_contains($path, 'multicurrency')) {
            return 'Multicurrency';
        } elseif (str_contains($path, 'ipsas')) {
            return 'IPSAS Compliance';
        } elseif (str_contains($path, 'finance')) {
            return 'Finance General';
        }

        return 'Unknown';
    }

    /**
     * Determine the action from request
     */
    private function determineAction(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        // Specific actions based on path patterns
        if (str_contains($path, 'process-payment')) {
            return 'Process Payment';
        } elseif (str_contains($path, 'bill-lookup')) {
            return 'Bill Lookup';
        } elseif (str_contains($path, 'convert')) {
            return 'Currency Conversion';
        } elseif (str_contains($path, 'update-rates')) {
            return 'Update Exchange Rates';
        } elseif (str_contains($path, 'compliance-report')) {
            return 'Generate Compliance Report';
        }

        // Generic actions based on HTTP method
        switch ($method) {
            case 'POST':
                return 'Create';
            case 'PUT':
            case 'PATCH':
                return 'Update';
            case 'DELETE':
                return 'Delete';
            case 'GET':
                return 'View';
            default:
                return 'Unknown Action';
        }
    }

    /**
     * Check if content is JSON
     */
    private function isJson(string $content): bool
    {
        json_decode($content);
        return json_last_error() === JSON_ERROR_NONE;
    }
}