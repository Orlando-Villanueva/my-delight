<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
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
            $this->setupCacheMonitoring();
            $this->setupPerformanceBenchmarks();
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

    /**
     * Setup cache hit rate monitoring.
     */
    private function setupCacheMonitoring(): void
    {
        Event::listen('cache:hit', function ($key, $value) {
            $hits = Cache::get('cache_hits', 0) + 1;
            Cache::put('cache_hits', $hits, now()->addDay());
            $this->updateCacheMetrics($hits, $this->getCacheMisses());
        });
        
        Event::listen('cache:missed', function ($key) {
            $misses = Cache::get('cache_misses', 0) + 1;
            Cache::put('cache_misses', $misses, now()->addDay());
            $this->updateCacheMetrics($this->getCacheHits(), $misses);
        });
    }

    /**
     * Setup performance benchmarks for critical user flows.
     */
    private function setupPerformanceBenchmarks(): void
    {
        // Dashboard loading benchmark
        Event::listen('dashboard.loading', function ($user, $startTime) {
            $endTime = microtime(true);
            $loadTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
            
            // Log if loading time exceeds threshold
            if ($loadTime > 500) { // 500ms threshold
                Log::channel('performance')->warning('Slow dashboard loading', [
                    'user_id' => $user->id,
                    'load_time_ms' => $loadTime,
                ]);
            }
            
            // Store metrics for analysis
            $this->storePerformanceMetric('dashboard_load', $loadTime);
        });
        
        // Reading log creation benchmark
        Event::listen('reading_log.created', function ($readingLog, $startTime) {
            $endTime = microtime(true);
            $processTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
            
            // Log if processing time exceeds threshold
            if ($processTime > 200) { // 200ms threshold
                Log::channel('performance')->warning('Slow reading log creation', [
                    'user_id' => $readingLog->user_id,
                    'process_time_ms' => $processTime,
                ]);
            }
            
            // Store metrics for analysis
            $this->storePerformanceMetric('reading_log_creation', $processTime);
        });
    }

    /**
     * Update cache hit rate metrics.
     */
    private function updateCacheMetrics(int $hits, int $misses): void
    {
        $total = $hits + $misses;
        
        if ($total > 0) {
            $hitRate = ($hits / $total) * 100;
            
            // Store hit rate for monitoring
            Cache::put('cache_hit_rate', $hitRate, now()->addHour());
            Cache::put('cache_hits', $hits, now()->addHour());
            Cache::put('cache_misses', $misses, now()->addHour());
            
            // Alert if hit rate is too low
            if ($total > 100 && $hitRate < 50) {
                Log::channel('performance')->warning('Low cache hit rate', [
                    'hit_rate' => $hitRate,
                    'hits' => $hits,
                    'misses' => $misses,
                ]);
            }
        }
    }

    /**
     * Get current cache hits count.
     */
    private function getCacheHits(): int
    {
        return Cache::get('cache_hits', 0);
    }

    /**
     * Get current cache misses count.
     */
    private function getCacheMisses(): int
    {
        return Cache::get('cache_misses', 0);
    }

    /**
     * Store performance metric for analysis.
     */
    private function storePerformanceMetric(string $metric, float $value): void
    {
        $metrics = Cache::get('performance_metrics', []);
        
        if (!isset($metrics[$metric])) {
            $metrics[$metric] = [
                'count' => 0,
                'total' => 0,
                'min' => PHP_FLOAT_MAX,
                'max' => 0,
            ];
        }
        
        $metrics[$metric]['count']++;
        $metrics[$metric]['total'] += $value;
        $metrics[$metric]['min'] = min($metrics[$metric]['min'], $value);
        $metrics[$metric]['max'] = max($metrics[$metric]['max'], $value);
        
        Cache::put('performance_metrics', $metrics, now()->addDay());
    }
}
