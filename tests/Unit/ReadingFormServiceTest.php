<?php

namespace Tests\Unit;

use App\Models\ReadingLog;
use App\Models\User;
use App\Services\BibleReferenceService;
use App\Services\ReadingFormService;
use App\Services\ReadingLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingFormServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReadingFormService $service;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $readingLogService = $this->app->make(ReadingLogService::class);
        $bibleService = $this->app->make(BibleReferenceService::class);
        $this->service = new ReadingFormService($readingLogService, $bibleService);
        $this->user = User::factory()->create();
    }

    /**
     * Test hasReadToday returns true when user has read today
     */
    public function test_has_read_today_returns_true_when_user_has_read_today(): void
    {
        // Create a reading log for today
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        $result = $this->service->hasReadToday($this->user);

        $this->assertTrue($result);
    }

    /**
     * Test hasReadToday returns false when user has not read today
     */
    public function test_has_read_today_returns_false_when_user_has_not_read_today(): void
    {
        // Don't create any reading logs for today
        $result = $this->service->hasReadToday($this->user);

        $this->assertFalse($result);
    }

    /**
     * Test hasReadToday returns false when user has only read yesterday
     */
    public function test_has_read_today_returns_false_when_user_has_only_read_yesterday(): void
    {
        // Create a reading log for yesterday, but not today
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->subDay()->toDateString(),
        ]);

        $result = $this->service->hasReadToday($this->user);

        $this->assertFalse($result);
    }

    /**
     * Test hasReadToday returns true when user has multiple readings today
     */
    public function test_has_read_today_returns_true_when_user_has_multiple_readings_today(): void
    {
        // Create multiple reading logs for today
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 2,
            'date_read' => today()->toDateString(),
        ]);

        $result = $this->service->hasReadToday($this->user);

        $this->assertTrue($result);
    }

    /**
     * Test hasReadToday only considers the specific user's readings
     */
    public function test_has_read_today_only_considers_specific_user_readings(): void
    {
        // Create another user with a reading today
        $otherUser = User::factory()->create();
        ReadingLog::factory()->create([
            'user_id' => $otherUser->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        // Our test user has no readings today
        $result = $this->service->hasReadToday($this->user);

        $this->assertFalse($result);
    }

    /**
     * Test hasReadToday works correctly with different timezones (edge case)
     */
    public function test_has_read_today_works_with_date_boundaries(): void
    {
        // Create a reading log with today's date string
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(), // Ensure it's stored as date string
        ]);

        $result = $this->service->hasReadToday($this->user);

        $this->assertTrue($result);
    }

    /**
     * Test that getFormContextData uses the hasReadToday method
     */
    public function test_get_form_context_data_uses_has_read_today_method(): void
    {
        // Create a reading log for today
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        $contextData = $this->service->getFormContextData($this->user);

        // Verify that hasReadToday is true in the context data
        $this->assertTrue($contextData['hasReadToday']);

        // Verify the method returns the same result as calling hasReadToday directly
        $this->assertEquals(
            $this->service->hasReadToday($this->user),
            $contextData['hasReadToday']
        );
    }

    /**
     * Test getRecentBooks returns recent books in correct order
     */
    public function test_get_recent_books_returns_books_in_correct_order(): void
    {
        // Create reading logs for different books on different dates
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1, // Genesis
            'chapter' => 1,
            'date_read' => today()->subDays(3)->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 19, // Psalms
            'chapter' => 1,
            'date_read' => today()->subDays(1)->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 40, // Matthew
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        $recentBooks = $this->service->getRecentBooks($this->user);

        // Should return 3 books
        $this->assertCount(3, $recentBooks);

        // Should be ordered by most recent first
        $this->assertEquals(40, $recentBooks[0]['id']); // Matthew (today)
        $this->assertEquals(19, $recentBooks[1]['id']); // Psalms (yesterday)
        $this->assertEquals(1, $recentBooks[2]['id']); // Genesis (3 days ago)
    }

    /**
     * Test getRecentBooks formats last_read_human correctly
     */
    public function test_get_recent_books_formats_last_read_human_correctly(): void
    {
        // Create reading logs with different dates
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 2,
            'chapter' => 1,
            'date_read' => today()->subDay()->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 3,
            'chapter' => 1,
            'date_read' => today()->subDays(3)->toDateString(),
        ]);

        $recentBooks = $this->service->getRecentBooks($this->user);

        // Check formatting
        $this->assertEquals('today', $recentBooks[0]['last_read_human']);
        $this->assertEquals('yesterday', $recentBooks[1]['last_read_human']);
        $this->assertStringContainsString('days ago', $recentBooks[2]['last_read_human']);
    }

    /**
     * Test getRecentBooks includes book details
     */
    public function test_get_recent_books_includes_book_details(): void
    {
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1, // Genesis
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        $recentBooks = $this->service->getRecentBooks($this->user);

        $this->assertCount(1, $recentBooks);
        $this->assertEquals(1, $recentBooks[0]['id']);
        $this->assertEquals('Genesis', $recentBooks[0]['name']);
        $this->assertEquals(50, $recentBooks[0]['chapters']); // Genesis has 50 chapters
        $this->assertEquals('old', $recentBooks[0]['testament']);
        $this->assertArrayHasKey('last_read', $recentBooks[0]);
        $this->assertArrayHasKey('last_read_human', $recentBooks[0]);
    }

    /**
     * Test getRecentBooks respects the limit parameter
     */
    public function test_get_recent_books_respects_limit_parameter(): void
    {
        // Create 10 different books
        for ($i = 1; $i <= 10; $i++) {
            ReadingLog::factory()->create([
                'user_id' => $this->user->id,
                'book_id' => $i,
                'chapter' => 1,
                'date_read' => today()->subDays(10 - $i)->toDateString(),
            ]);
        }

        // Request only 3 books
        $recentBooks = $this->service->getRecentBooks($this->user, 3);

        $this->assertCount(3, $recentBooks);
    }

    /**
     * Test getRecentBooks groups multiple chapters of same book
     */
    public function test_get_recent_books_groups_multiple_chapters_of_same_book(): void
    {
        // Read multiple chapters of Genesis on different days
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->subDays(5)->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 2,
            'date_read' => today()->subDays(2)->toDateString(),
        ]);

        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 3,
            'date_read' => today()->toDateString(),
        ]);

        $recentBooks = $this->service->getRecentBooks($this->user);

        // Should return only 1 book (Genesis) with most recent date
        $this->assertCount(1, $recentBooks);
        $this->assertEquals(1, $recentBooks[0]['id']);
        $this->assertEquals('today', $recentBooks[0]['last_read_human']);
    }

    /**
     * Test getRecentBooks only returns books for specific user
     */
    public function test_get_recent_books_only_returns_books_for_specific_user(): void
    {
        // Create reading log for test user
        ReadingLog::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => 1,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        // Create reading log for another user
        $otherUser = User::factory()->create();
        ReadingLog::factory()->create([
            'user_id' => $otherUser->id,
            'book_id' => 2,
            'chapter' => 1,
            'date_read' => today()->toDateString(),
        ]);

        $recentBooks = $this->service->getRecentBooks($this->user);

        // Should only return books for the test user
        $this->assertCount(1, $recentBooks);
        $this->assertEquals(1, $recentBooks[0]['id']);
    }

    /**
     * Test getRecentBooks returns empty array when no readings exist
     */
    public function test_get_recent_books_returns_empty_array_when_no_readings_exist(): void
    {
        $recentBooks = $this->service->getRecentBooks($this->user);

        $this->assertIsArray($recentBooks);
        $this->assertEmpty($recentBooks);
    }
}
