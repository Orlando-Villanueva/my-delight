<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitorCacheHitRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:cache-hit-rate {--alert-threshold=50 : Alert threshold percentage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor cache hit rate and alert if below threshold';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hits = Cache::get('cache_hits', 0);
        $misses = Cache::get('cache_misses', 0);
        $total = $hits + $misses;

        if ($total === 0) {
            $this->info('No cache activity recorded yet.');
            return 0;
        }

        $hitRate = ($hits / $total) * 100;
        $threshold = (int) $this->option('alert-threshold');

        $this->info("Cache Hit Rate: {$hitRate}%");
        $this->info("Total Hits: {$hits}");
        $this->info("Total Misses: {$misses}");
        $this->info("Total Requests: {$total}");

        // Alert if hit rate is below threshold
        if ($hitRate < $threshold) {
            $this->error("Cache hit rate is below threshold ({$threshold}%)!");

            // Log the alert
            Log::channel('performance')->warning('Low cache hit rate detected', [
                'hit_rate' => $hitRate,
                'threshold' => $threshold,
                'hits' => $hits,
                'misses' => $misses,
            ]);

            return 1;
        }

        return 0;
    }
}
