<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PerformanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only enable in non-production environments or when explicitly enabled
        if (app()->environment('local', 'testing', 'development') || config('app.performance_monitoring')) {
            $this->setupQueryMonitoring();
            // Cache monitoring removed - too complex for MVP and has critical issues
            // $this->setupCacheMonitoring();
            // $this->setupPerformanceBenchmarks();
        }
    }

    /**
     * Setup query monitoring for critical flows.
     */
    private function setupQueryMonitoring(): void
    {
        // Log slow queries (over 100ms)
        DB::listen(function ($query) {
            $time = $query->time;

            if ($time > 100) {
                Log::channel('performance')->warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $time,
                    'connection' => $query->connectionName,
                ]);
            }
        });
    }
}
