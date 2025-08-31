<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PosOnlyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        
        // If user is POS operator, only allow access to POS routes
        if ($user->isPosOperator()) {
            $allowedRoutes = [
                'finance.pos.*',
                'finance.api.payment-methods',
                'finance.api.pos-terminals',
                'dashboard' // Allow dashboard access
            ];

            $currentRoute = $request->route()->getName();
            $isAllowed = false;

            foreach ($allowedRoutes as $pattern) {
                if (fnmatch($pattern, $currentRoute)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                return redirect()->route('finance.pos.index')
                    ->with('error', 'Access denied. You only have access to POS system.');
            }
        }

        return $next($request);
    }
}
