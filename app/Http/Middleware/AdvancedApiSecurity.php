<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class AdvancedApiSecurity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Log API access attempt
        Log::info('API Access Attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);

        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request)) {
            Log::warning('Suspicious API Activity Detected', [
                'ip' => $request->ip(),
                'endpoint' => $request->path(),
                'reason' => 'Rate limit exceeded or unusual pattern'
            ]);
            
            return response()->json([
                'error' => 'Too many requests. Please try again later.',
                'code' => 'RATE_LIMITED'
            ], 429);
        }

        // Validate API key format if present
        if ($request->header('X-API-Key') && !$this->isValidApiKeyFormat($request->header('X-API-Key'))) {
            Log::warning('Invalid API Key Format', [
                'ip' => $request->ip(),
                'endpoint' => $request->path()
            ]);
            
            return response()->json([
                'error' => 'Invalid API key format',
                'code' => 'INVALID_API_KEY'
            ], 401);
        }

        // Add security headers
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");

        // Add API versioning header
        $response->headers->set('X-API-Version', '1.0');
        $response->headers->set('X-RateLimit-Limit', '1000');
        $response->headers->set('X-RateLimit-Remaining', $this->getRemainingRequests($request));

        return $response;
    }

    /**
     * Check for suspicious activity patterns
     */
    private function isSuspiciousActivity(Request $request): bool
    {
        $ip = $request->ip();
        $cacheKey = "api_requests:{$ip}";
        
        // Get current request count for this IP
        $requestCount = Cache::get($cacheKey, 0);
        
        // Increment counter
        Cache::put($cacheKey, $requestCount + 1, now()->addMinute());
        
        // Check if exceeded rate limit (100 requests per minute per IP)
        return $requestCount > 100;
    }

    /**
     * Validate API key format
     */
    private function isValidApiKeyFormat(string $apiKey): bool
    {
        // API key should be 40 characters long and alphanumeric
        return preg_match('/^[a-zA-Z0-9]{40}$/', $apiKey);
    }

    /**
     * Get remaining requests for rate limiting header
     */
    private function getRemainingRequests(Request $request): int
    {
        $ip = $request->ip();
        $cacheKey = "api_requests:{$ip}";
        $requestCount = Cache::get($cacheKey, 0);
        
        return max(0, 100 - $requestCount);
    }
}