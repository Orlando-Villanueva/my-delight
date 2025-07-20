<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ViewPerformanceMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:performance-metrics {--reset : Reset metrics after viewing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View performance metrics for critical user flows';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $metrics = Cache::get('performance_metrics', []);
        
        if (empty($metrics)) {
            $this->info('No performance metrics recorded yet.');
            return 0;
        }
        
        $headers = ['Metric', 'Count', 'Avg (ms)', 'Min (ms)', 'Max (ms)'];
        $rows = [];
        
        foreach ($metrics as $metric => $data) {
            $avg = $data['count'] > 0 ? $data['total'] / $data['count'] : 0;
            
            $rows[] = [
                $metric,
                $data['count'],
                number_format($avg, 2),
                number_format($data['min'], 2),
                number_format($data['max'], 2),
            ];
        }
        
        $this->table($headers, $rows);
        
        // Reset metrics if requested
        if ($this->option('reset')) {
            Cache::forget('performance_metrics');
            $this->info('Performance metrics have been reset.');
        }
        
        return 0;
    }
}
