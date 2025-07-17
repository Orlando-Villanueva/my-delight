<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\BookProgressSyncService;
use Illuminate\Console\Command;

class SyncBookProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bible:sync-progress {user_id? : The ID of the user to sync (all users if omitted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize book progress with reading logs';

    /**
     * Execute the console command.
     */
    public function handle(BookProgressSyncService $syncService)
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            
            $this->info("Syncing book progress for user: {$user->name}");
            $stats = $syncService->syncBookProgressForUser($user);
            
            $this->info("Processed {$stats['processed_logs']} reading logs.");
            $this->info("Updated progress for {$stats['updated_books_count']} books.");
        } else {
            $this->info("Syncing book progress for all users...");
            $stats = $syncService->syncBookProgressForAllUsers();
            
            $this->info("Processed {$stats['users_processed']} users.");
            $this->info("Processed {$stats['total_logs_processed']} reading logs.");
            $this->info("Updated progress for {$stats['total_books_updated']} books.");
        }
        
        $this->info("Book progress synchronization completed successfully.");
        return 0;
    }
}