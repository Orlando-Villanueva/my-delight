<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ReadingLog;
use App\Services\BookProgressSyncService;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $seedUser = User::factory()->create([
            'name' => 'Seed User',
            'email' => 'seed.user@example.com',
        ]);

        // Create varied reading logs for testing filters
        $this->createTestReadingLogs($seedUser);

        // Sync book progress with the seeded reading logs
        $this->command->info("Syncing book progress for seeded reading logs...");
        $syncService = app(BookProgressSyncService::class);
        $stats = $syncService->syncBookProgressForUser($seedUser);
        $this->command->info("Synced {$stats['processed_logs']} reading logs and updated {$stats['updated_books_count']} books with book progress.");
    }

    /**
     * Create test reading logs across different time periods to test filtering
     */
    private function createTestReadingLogs(User $user): void
    {
        $today = Carbon::today();

        // Recent readings (last 7 days) - 3 entries (reduced activity recently)
        $recentLogs = [
            ['book_id' => 43, 'chapter' => 1, 'passage_text' => 'John 1', 'date' => $today->copy()->subDay(), 'notes' => 'In the beginning was the Word. Beautiful opening to John\'s Gospel.'],
            ['book_id' => 19, 'chapter' => 23, 'passage_text' => 'Psalms 23', 'date' => $today->copy()->subDays(3), 'notes' => 'The Lord is my shepherd. Such comfort in this psalm.'],
            ['book_id' => 45, 'chapter' => 8, 'passage_text' => 'Romans 8', 'date' => $today->copy()->subDays(5), 'notes' => 'No condemnation for those in Christ Jesus. Powerful chapter!'],
        ];

        // Medium-term readings (8-30 days ago) - 6 entries
        $mediumLogs = [
            ['book_id' => 19, 'chapter' => 91, 'passage_text' => 'Psalms 91', 'date' => $today->copy()->subDays(10), 'notes' => 'He who dwells in the shelter of the Most High...'],
            ['book_id' => 20, 'chapter' => 3, 'passage_text' => 'Proverbs 3', 'date' => $today->copy()->subDays(15), 'notes' => 'Trust in the Lord with all your heart. Lean not on your own understanding.'],
            ['book_id' => 49, 'chapter' => 6, 'passage_text' => 'Ephesians 6', 'date' => $today->copy()->subDays(18), 'notes' => 'Armor of God. Stand firm against the schemes of the devil.'],
            ['book_id' => 58, 'chapter' => 11, 'passage_text' => 'Hebrews 11', 'date' => $today->copy()->subDays(22), 'notes' => 'Faith chapter. Now faith is the assurance of things hoped for...'],
            ['book_id' => 42, 'chapter' => 15, 'passage_text' => 'Luke 15', 'date' => $today->copy()->subDays(25), 'notes' => 'Parables of the lost sheep, coin, and prodigal son. God\'s heart for the lost.'],
            ['book_id' => 23, 'chapter' => 53, 'passage_text' => 'Isaiah 53', 'date' => $today->copy()->subDays(28), 'notes' => 'Suffering servant. He was pierced for our transgressions.'],
        ];

        // Older readings (31-90 days ago) - 5 entries
        $olderLogs = [
            ['book_id' => 66, 'chapter' => 21, 'passage_text' => 'Revelation 21-22', 'date' => $today->copy()->subDays(35), 'notes' => 'New heaven and new earth. No more tears, pain, or death!'],
            ['book_id' => 8, 'chapter' => 1, 'passage_text' => 'Ruth 1-4', 'date' => $today->copy()->subDays(45), 'notes' => 'Beautiful story of loyalty and redemption. Where you go, I will go.'],
            ['book_id' => 27, 'chapter' => 6, 'passage_text' => 'Daniel 6', 'date' => $today->copy()->subDays(60), 'notes' => 'Daniel in the lion\'s den. God shut the mouths of the lions.'],
            ['book_id' => 32, 'chapter' => 1, 'passage_text' => 'Jonah 1-4', 'date' => $today->copy()->subDays(75), 'notes' => 'Jonah and the great fish. God\'s mercy extends even to Nineveh.'],
            ['book_id' => 21, 'chapter' => 3, 'passage_text' => 'Ecclesiastes 3', 'date' => $today->copy()->subDays(85), 'notes' => 'To everything there is a season. A time for every purpose under heaven.'],
        ];

        // Very old readings (over 90 days ago) - 18 entries (extensive reading history)
        $veryOldLogs = [
            ['book_id' => 2, 'chapter' => 20, 'passage_text' => 'Exodus 20', 'date' => $today->copy()->subDays(95), 'notes' => 'The Ten Commandments. God\'s moral law for His people.'],
            ['book_id' => 44, 'chapter' => 2, 'passage_text' => 'Acts 2', 'date' => $today->copy()->subDays(105), 'notes' => 'Pentecost! The Holy Spirit comes with power. Birth of the church.'],
            ['book_id' => 50, 'chapter' => 4, 'passage_text' => 'Philippians 4', 'date' => $today->copy()->subDays(115), 'notes' => 'Rejoice in the Lord always! I can do all things through Christ.'],
            ['book_id' => 18, 'chapter' => 1, 'passage_text' => 'Job 1-2', 'date' => $today->copy()->subDays(125), 'notes' => 'The testing of Job. Though He slay me, yet will I trust in Him.'],
            ['book_id' => 1, 'chapter' => 1, 'passage_text' => 'Genesis 1', 'date' => $today->copy()->subDays(135), 'notes' => 'In the beginning God created... The foundation of everything.'],
            ['book_id' => 40, 'chapter' => 5, 'passage_text' => 'Matthew 5-7', 'date' => $today->copy()->subDays(145), 'notes' => 'The Sermon on the Mount. Beatitudes and Jesus\' teachings.'],
            ['book_id' => 46, 'chapter' => 13, 'passage_text' => '1 Corinthians 13', 'date' => $today->copy()->subDays(155), 'notes' => 'Love chapter. Though I speak with tongues of angels...'],
            ['book_id' => 43, 'chapter' => 2, 'passage_text' => 'John 2', 'date' => $today->copy()->subDays(165), 'notes' => 'Wedding at Cana - Jesus\' first miracle. Water to wine!'],
            ['book_id' => 43, 'chapter' => 3, 'passage_text' => 'John 3', 'date' => $today->copy()->subDays(175), 'notes' => 'Nicodemus and being born again. John 3:16 - the Gospel in a nutshell.'],
            ['book_id' => 19, 'chapter' => 1, 'passage_text' => 'Psalms 1', 'date' => $today->copy()->subDays(185), 'notes' => 'Blessed is the man who walks not in the counsel of the wicked.'],
            ['book_id' => 20, 'chapter' => 31, 'passage_text' => 'Proverbs 31', 'date' => $today->copy()->subDays(195), 'notes' => 'The virtuous woman. Her worth is far above rubies.'],
            ['book_id' => 47, 'chapter' => 5, 'passage_text' => '2 Corinthians 5', 'date' => $today->copy()->subDays(205), 'notes' => 'We are ambassadors for Christ. New creation in Him.'],
            ['book_id' => 51, 'chapter' => 2, 'passage_text' => 'Colossians 2', 'date' => $today->copy()->subDays(215), 'notes' => 'Rooted and built up in Him. Beware of philosophy and empty deceit.'],
            ['book_id' => 52, 'chapter' => 4, 'passage_text' => '1 Thessalonians 4', 'date' => $today->copy()->subDays(225), 'notes' => 'The coming of the Lord. We will be caught up together.'],
            ['book_id' => 56, 'chapter' => 3, 'passage_text' => 'Titus 3', 'date' => $today->copy()->subDays(235), 'notes' => 'He saved us through the washing of regeneration.'],
            ['book_id' => 60, 'chapter' => 1, 'passage_text' => '1 Peter 1', 'date' => $today->copy()->subDays(245), 'notes' => 'Born again to a living hope through the resurrection.'],
            ['book_id' => 62, 'chapter' => 1, 'passage_text' => '1 John 1', 'date' => $today->copy()->subDays(255), 'notes' => 'That which was from the beginning. Fellowship with the Father.'],
            ['book_id' => 66, 'chapter' => 1, 'passage_text' => 'Revelation 1', 'date' => $today->copy()->subDays(265), 'notes' => 'The revelation of Jesus Christ. Alpha and Omega.'],
        ];

        // Combine all logs
        $allLogs = array_merge($recentLogs, $mediumLogs, $olderLogs, $veryOldLogs);

        // Create the reading logs with realistic timestamps
        foreach ($allLogs as $logData) {
            // Calculate realistic created_at timestamp
            // Most people log their reading within a few hours of reading
            // Add some randomness: 0-6 hours after the reading date
            $readingDate = $logData['date'];
            $loggedAt = $readingDate->copy()
                ->addHours(rand(0, 6))
                ->addMinutes(rand(0, 59));

            ReadingLog::create([
                'user_id' => $user->id,
                'book_id' => $logData['book_id'],
                'chapter' => $logData['chapter'],
                'passage_text' => $logData['passage_text'],
                'date_read' => $readingDate->toDateString(),
                'notes_text' => $logData['notes'],
                'created_at' => $loggedAt,
                'updated_at' => $loggedAt,
            ]);
        }

        $this->command->info("Created " . count($allLogs) . " test reading logs for {$user->name}");
        $this->command->info("- Last 7 days: " . count($recentLogs) . " logs");
        $this->command->info("- Last 30 days: " . (count($recentLogs) + count($mediumLogs)) . " logs total");
        $this->command->info("- Last 90 days: " . (count($recentLogs) + count($mediumLogs) + count($olderLogs)) . " logs total");
        $this->command->info("- All time: " . count($allLogs) . " logs total");
        $this->command->info("- Historical entries (90+ days): " . count($veryOldLogs) . " logs");
    }
}
