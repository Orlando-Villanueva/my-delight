<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class QueryLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only enable query logging in non-production environments
        if (app()->environment('local', 'testing', 'development')) {
            // Enable query logging
            DB::enableQueryLog();
            
            // Process the request
            $response = $next($request);
            
            // Get the query log
            $queries = DB::getQueryLog();
            
            // Log slow queries (over 100ms)
            $slowQueries = collect($queries)->filter(function ($query) {
                return $query['time'] > 100; // 100ms threshold
            });
            
            if ($slowQueries->isNotEmpty()) {
                Log::channel('query')->warning('Slow queries detected:', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'queries' => $slowQueries->toArray(),
                ]);
            }
            
            // Log all queries if debug mode is enabled
            if (config('app.debug')) {
                Log::channel('query')->debug('Queries for ' . $request->fullUrl(), [
                    'count' => count($queries),
                    'queries' => $queries,
                ]);
            }
            
            return $response;
        }
        
        return $next($request);
    }
}
