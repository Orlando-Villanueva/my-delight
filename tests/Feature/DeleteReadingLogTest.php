<?php

namespace Tests\Feature;

use App\Models\ReadingLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteReadingLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can delete their own reading log
     */
    public function test_user_can_delete_own_reading_log(): void
    {
        $user = User::factory()->create();

        $log = ReadingLog::factory()->create([
            'user_id' => $user->id,
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today(),
        ]);

        $response = $this->actingAs($user)->delete(route('logs.destroy', $log));

        $response->assertRedirect(route('logs.index'));
        $this->assertDatabaseMissing('reading_logs', [
            'id' => $log->id,
        ]);
    }

    /**
     * Test user cannot delete another user's reading log
     */
    public function test_user_cannot_delete_another_users_reading_log(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $log = ReadingLog::factory()->create([
            'user_id' => $user1->id,
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today(),
        ]);

        $response = $this->actingAs($user2)->delete(route('logs.destroy', $log));

        $response->assertForbidden();
        $this->assertDatabaseHas('reading_logs', [
            'id' => $log->id,
        ]);
    }

    /**
     * Test unauthenticated user cannot delete reading log
     */
    public function test_unauthenticated_user_cannot_delete_reading_log(): void
    {
        $log = ReadingLog::factory()->create([
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today(),
        ]);

        $response = $this->delete(route('logs.destroy', $log));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('reading_logs', [
            'id' => $log->id,
        ]);
    }

    /**
     * Test deleting reading log updates book progress correctly
     */
    public function test_deleting_reading_log_updates_book_progress(): void
    {
        $user = User::factory()->create();

        // Create reading logs using the service (which creates book progress)
        $readingLogService = app(\App\Services\ReadingLogService::class);

        $log1 = $readingLogService->logReading($user, [
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today()->toDateString(),
        ]);

        $log2 = $readingLogService->logReading($user, [
            'book_id' => 1,
            'chapter' => 2,
            'passage_text' => 'Genesis 2',
            'date_read' => today()->toDateString(),
        ]);

        // Verify book progress exists
        $this->assertDatabaseHas('book_progress', [
            'user_id' => $user->id,
            'book_id' => 1,
        ]);

        $bookProgress = $user->bookProgress()->where('book_id', 1)->first();
        $this->assertTrue(in_array(1, $bookProgress->chapters_read));
        $this->assertTrue(in_array(2, $bookProgress->chapters_read));

        // Delete one chapter
        $this->actingAs($user)->delete(route('logs.destroy', $log1));

        // Verify book progress updated
        $bookProgress->refresh();
        $this->assertFalse(in_array(1, $bookProgress->chapters_read));
        $this->assertTrue(in_array(2, $bookProgress->chapters_read));
    }

    /**
     * Test HTMX request returns updated log list
     */
    public function test_htmx_delete_returns_updated_log_list(): void
    {
        $user = User::factory()->create();

        $log = ReadingLog::factory()->create([
            'user_id' => $user->id,
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today(),
        ]);

        $response = $this->actingAs($user)
            ->withHeaders(['HX-Request' => 'true'])
            ->delete(route('logs.destroy', $log));

        $response->assertSuccessful();
        $response->assertViewIs('partials.reading-log-list');
        $response->assertHeader('HX-Trigger', 'readingLogDeleted');
    }

    /**
     * Test deleting all chapters from a range removes entire card
     */
    public function test_deleting_all_chapters_from_range_removes_card(): void
    {
        $user = User::factory()->create();

        // Create a range of chapters (Genesis 1-3)
        $logs = [];
        for ($chapter = 1; $chapter <= 3; $chapter++) {
            $logs[] = ReadingLog::factory()->create([
                'user_id' => $user->id,
                'book_id' => 1,
                'chapter' => $chapter,
                'passage_text' => 'Genesis 1-3',
                'date_read' => today(),
            ]);
        }

        // Delete all chapters
        foreach ($logs as $log) {
            $this->actingAs($user)->delete(route('logs.destroy', $log));
        }

        // Verify all logs are deleted
        foreach ($logs as $log) {
            $this->assertDatabaseMissing('reading_logs', [
                'id' => $log->id,
            ]);
        }

        // Verify logs page shows empty state or no trace of the range
        $response = $this->actingAs($user)->get(route('logs.index'));
        $response->assertDontSee('Genesis 1-3');
    }

    /**
     * Test deleting one chapter from a range keeps remaining chapters
     */
    public function test_deleting_one_chapter_from_range_keeps_remaining(): void
    {
        $user = User::factory()->create();

        // Create a range of chapters (Genesis 1-3) using the service
        $readingLogService = app(\App\Services\ReadingLogService::class);

        $createdLog = $readingLogService->logReading($user, [
            'book_id' => 1,
            'chapters' => [1, 2, 3],
            'passage_text' => 'Genesis 1-3',
            'date_read' => today()->toDateString(),
        ]);

        // Get all the created logs
        $allLogs = $user->readingLogs()->where('book_id', 1)->get();
        $log1 = $allLogs->where('chapter', 1)->first();
        $log2 = $allLogs->where('chapter', 2)->first();
        $log3 = $allLogs->where('chapter', 3)->first();

        // Delete middle chapter
        $this->actingAs($user)->delete(route('logs.destroy', $log2));

        // Verify only the deleted log is missing
        $this->assertDatabaseMissing('reading_logs', ['id' => $log2->id]);
        $this->assertDatabaseHas('reading_logs', ['id' => $log1->id]);
        $this->assertDatabaseHas('reading_logs', ['id' => $log3->id]);

        // Verify book progress reflects the deletion
        $bookProgress = $user->bookProgress()->where('book_id', 1)->first();
        $this->assertTrue(in_array(1, $bookProgress->chapters_read));
        $this->assertFalse(in_array(2, $bookProgress->chapters_read));
        $this->assertTrue(in_array(3, $bookProgress->chapters_read));
    }

    /**
     * Test dashboard loads successfully after deletion (cache invalidation)
     */
    public function test_dashboard_loads_after_deletion(): void
    {
        $user = User::factory()->create();

        // Create reading log using the service
        $readingLogService = app(\App\Services\ReadingLogService::class);

        $log = $readingLogService->logReading($user, [
            'book_id' => 1,
            'chapter' => 1,
            'passage_text' => 'Genesis 1',
            'date_read' => today()->toDateString(),
        ]);

        // Load dashboard to populate cache
        $this->actingAs($user)->get('/dashboard');

        // Delete the log
        $this->actingAs($user)->delete(route('logs.destroy', $log));

        // Verify dashboard still loads successfully after deletion
        // (This verifies cache is properly invalidated and dashboard doesn't crash)
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertSuccessful();
        $response->assertSee('Dashboard');

        // Verify the deleted reading doesn't appear
        $response->assertDontSee('Genesis 1');
    }
}
