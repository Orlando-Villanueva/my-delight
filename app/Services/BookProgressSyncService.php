<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReadingLog;
use Illuminate\Support\Facades\DB;

class BookProgressSyncService
{
    private ReadingLogService $readingLogService;

    public function __construct(
        ReadingLogService $readingLogService
    ) {
        $this->readingLogService = $readingLogService;
    }

    /**
     * Sync book progress for a user based on their reading logs.
     * This ensures seeded reading logs are properly reflected in book progress.
     */
    public function syncBookProgressForUser(User $user): array
    {
        // Get all reading logs for the user
        $readingLogs = $user->readingLogs()->get();
        
        // Reset book progress for the user
        $user->bookProgress()->delete();
        
        // Track stats for reporting
        $stats = [
            'processed_logs' => 0,
            'updated_books' => []
        ];
        
        // Process each reading log
        foreach ($readingLogs as $log) {
            // Use the existing method to update book progress
            $this->readingLogService->updateBookProgressFromLog($log);
            $stats['processed_logs']++;
            
            // Track unique books that were updated
            if (!in_array($log->book_id, $stats['updated_books'])) {
                $stats['updated_books'][] = $log->book_id;
            }
        }
        
        $stats['updated_books_count'] = count($stats['updated_books']);
        
        return $stats;
    }

    /**
     * Sync book progress for all users.
     */
    public function syncBookProgressForAllUsers(): array
    {
        $userIds = ReadingLog::select('user_id')
            ->distinct()
            ->pluck('user_id');
            
        $stats = [
            'users_processed' => 0,
            'total_logs_processed' => 0,
            'total_books_updated' => 0
        ];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $userStats = $this->syncBookProgressForUser($user);
                $stats['users_processed']++;
                $stats['total_logs_processed'] += $userStats['processed_logs'];
                $stats['total_books_updated'] += $userStats['updated_books_count'];
            }
        }
        
        return $stats;
    }
}