<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ReadingLog;
use App\Services\BookProgressSyncService;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
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

        // Clear all caches to ensure fresh statistics
        $this->command->info("Clearing application caches...");
        cache()->flush();
        $this->command->info("All caches cleared.");
    }

    /**
     * Create test reading logs concentrated in last 6 weeks for realistic usage pattern
     * Target: ~2.4 readings per day over 42 days = ~100 total readings
     */
    private function createTestReadingLogs(User $user): void
    {
        // Create a Faker instance with a fixed seed for reproducible results
        $faker = FakerFactory::create();
        $faker->seed(12345); // Fixed seed ensures consistent random values

        $today = Carbon::today();

        // 6-week concentrated reading pattern (~100 total readings)
        // Some days with 1 reading, some with 3-4, averaging 2.4/day
        $allLogs = [
            // Week 1 (Days 1-7) - 18 readings
            ['book_id' => 43, 'chapter' => 1, 'passage_text' => 'John 1', 'date' => $today->copy()->subDay(), 'notes' => 'In the beginning was the Word. Beautiful opening to John\'s Gospel.'],
            ['book_id' => 19, 'chapter' => 1, 'passage_text' => 'Psalms 1', 'date' => $today->copy()->subDay(), 'notes' => 'Blessed is the man who walks not in the counsel of the wicked.'],
            
            ['book_id' => 19, 'chapter' => 23, 'passage_text' => 'Psalms 23', 'date' => $today->copy()->subDays(2), 'notes' => 'The Lord is my shepherd. Such comfort in this psalm.'],
            ['book_id' => 20, 'chapter' => 3, 'passage_text' => 'Proverbs 3', 'date' => $today->copy()->subDays(2), 'notes' => 'Trust in the Lord with all your heart. Lean not on your own understanding.'],
            ['book_id' => 40, 'chapter' => 5, 'passage_text' => 'Matthew 5', 'date' => $today->copy()->subDays(2), 'notes' => 'The Sermon on the Mount. Blessed are the poor in spirit.'],

            ['book_id' => 45, 'chapter' => 8, 'passage_text' => 'Romans 8', 'date' => $today->copy()->subDays(3), 'notes' => 'No condemnation for those in Christ Jesus. Powerful chapter!'],
            ['book_id' => 45, 'chapter' => 9, 'passage_text' => 'Romans 9', 'date' => $today->copy()->subDays(3), 'notes' => 'God\'s sovereign choice. Vessels of mercy prepared for glory.'],

            ['book_id' => 43, 'chapter' => 14, 'passage_text' => 'John 14', 'date' => $today->copy()->subDays(4), 'notes' => 'I am the way, the truth, and the life. Let not your heart be troubled.'],
            ['book_id' => 40, 'chapter' => 6, 'passage_text' => 'Matthew 6', 'date' => $today->copy()->subDays(4), 'notes' => 'The Lord\'s Prayer. Do not worry about tomorrow.'],
            ['book_id' => 44, 'chapter' => 1, 'passage_text' => 'Acts 1', 'date' => $today->copy()->subDays(4), 'notes' => 'You will receive power when the Holy Spirit comes upon you.'],

            ['book_id' => 19, 'chapter' => 91, 'passage_text' => 'Psalms 91', 'date' => $today->copy()->subDays(5), 'notes' => 'He who dwells in the shelter of the Most High...'],
            ['book_id' => 19, 'chapter' => 92, 'passage_text' => 'Psalms 92', 'date' => $today->copy()->subDays(5), 'notes' => 'It is good to give thanks to the LORD. A Sabbath psalm of gratitude.'],

            ['book_id' => 41, 'chapter' => 16, 'passage_text' => 'Mark 16', 'date' => $today->copy()->subDays(6), 'notes' => 'The resurrection of Jesus. Go into all the world and preach the gospel.'],
            ['book_id' => 20, 'chapter' => 4, 'passage_text' => 'Proverbs 4', 'date' => $today->copy()->subDays(6), 'notes' => 'Get wisdom, get understanding. Do not forget my words or turn away from them.'],
            ['book_id' => 48, 'chapter' => 5, 'passage_text' => 'Galatians 5', 'date' => $today->copy()->subDays(6), 'notes' => 'The fruit of the Spirit. Walk in the Spirit and you will not fulfill the lust of the flesh.'],

            ['book_id' => 49, 'chapter' => 6, 'passage_text' => 'Ephesians 6', 'date' => $today->copy()->subDays(7), 'notes' => 'Armor of God. Stand firm against the schemes of the devil.'],
            ['book_id' => 59, 'chapter' => 4, 'passage_text' => 'James 4', 'date' => $today->copy()->subDays(7), 'notes' => 'Submit to God. Resist the devil and he will flee from you.'],
            ['book_id' => 19, 'chapter' => 118, 'passage_text' => 'Psalms 118', 'date' => $today->copy()->subDays(7), 'notes' => 'This is the day the Lord has made; let us rejoice and be glad in it.'],

            // Week 2 (Days 8-14) - 16 readings
            ['book_id' => 19, 'chapter' => 119, 'passage_text' => 'Psalms 119', 'date' => $today->copy()->subDays(8), 'notes' => 'Your word is a lamp to my feet and a light to my path.'],
            ['book_id' => 58, 'chapter' => 11, 'passage_text' => 'Hebrews 11', 'date' => $today->copy()->subDays(8), 'notes' => 'Faith chapter. Now faith is the assurance of things hoped for...'],

            ['book_id' => 42, 'chapter' => 15, 'passage_text' => 'Luke 15', 'date' => $today->copy()->subDays(9), 'notes' => 'Parables of the lost sheep, coin, and prodigal son. God\'s heart for the lost.'],
            ['book_id' => 23, 'chapter' => 53, 'passage_text' => 'Isaiah 53', 'date' => $today->copy()->subDays(9), 'notes' => 'Suffering servant. He was pierced for our transgressions.'],
            ['book_id' => 66, 'chapter' => 21, 'passage_text' => 'Revelation 21', 'date' => $today->copy()->subDays(9), 'notes' => 'New heaven and new earth. No more tears, pain, or death!'],

            ['book_id' => 66, 'chapter' => 22, 'passage_text' => 'Revelation 22', 'date' => $today->copy()->subDays(10), 'notes' => 'The river of life. "I am coming soon." Amen. Come, Lord Jesus!'],

            ['book_id' => 26, 'chapter' => 4, 'passage_text' => 'Ezekiel 4', 'date' => $today->copy()->subDays(11), 'notes' => 'The symbolic siege of Jerusalem. A powerful visual prophecy.'],
            ['book_id' => 8, 'chapter' => 1, 'passage_text' => 'Ruth 1', 'date' => $today->copy()->subDays(11), 'notes' => 'Naomi and Ruth - "Where you go, I will go."'],

            ['book_id' => 8, 'chapter' => 2, 'passage_text' => 'Ruth 2', 'date' => $today->copy()->subDays(12), 'notes' => 'Ruth meets Boaz in the fields. He shows her kindness.'],
            ['book_id' => 8, 'chapter' => 3, 'passage_text' => 'Ruth 3', 'date' => $today->copy()->subDays(12), 'notes' => 'Ruth approaches Boaz at the threshing floor.'],
            ['book_id' => 8, 'chapter' => 4, 'passage_text' => 'Ruth 4', 'date' => $today->copy()->subDays(12), 'notes' => 'Boaz redeems Ruth. Their son Obed becomes David\'s grandfather.'],

            ['book_id' => 28, 'chapter' => 3, 'passage_text' => 'Hosea 3', 'date' => $today->copy()->subDays(13), 'notes' => 'God\'s redeeming love for Israel despite their unfaithfulness.'],
            ['book_id' => 27, 'chapter' => 6, 'passage_text' => 'Daniel 6', 'date' => $today->copy()->subDays(13), 'notes' => 'Daniel in the lion\'s den. God shut the mouths of the lions.'],

            ['book_id' => 30, 'chapter' => 3, 'passage_text' => 'Amos 3', 'date' => $today->copy()->subDays(14), 'notes' => 'The lion has roared; who will not fear? The Lord GOD has spoken; who can but prophesy?'],
            ['book_id' => 33, 'chapter' => 3, 'passage_text' => 'Micah 3', 'date' => $today->copy()->subDays(14), 'notes' => 'Judgment against Israel\'s leaders who perverted justice.'],
            ['book_id' => 21, 'chapter' => 3, 'passage_text' => 'Ecclesiastes 3', 'date' => $today->copy()->subDays(14), 'notes' => 'To everything there is a season. A time for every purpose under heaven.'],

            // Week 3 (Days 15-21) - 17 readings
            ['book_id' => 2, 'chapter' => 20, 'passage_text' => 'Exodus 20', 'date' => $today->copy()->subDays(15), 'notes' => 'The Ten Commandments. God\'s moral law for His people.'],
            ['book_id' => 44, 'chapter' => 2, 'passage_text' => 'Acts 2', 'date' => $today->copy()->subDays(15), 'notes' => 'Pentecost! The Holy Spirit comes with power. Birth of the church.'],

            ['book_id' => 50, 'chapter' => 4, 'passage_text' => 'Philippians 4', 'date' => $today->copy()->subDays(16), 'notes' => 'Rejoice in the Lord always! I can do all things through Christ.'],
            ['book_id' => 18, 'chapter' => 1, 'passage_text' => 'Job 1-2', 'date' => $today->copy()->subDays(16), 'notes' => 'The testing of Job. Though He slay me, yet will I trust in Him.'],
            ['book_id' => 1, 'chapter' => 1, 'passage_text' => 'Genesis 1', 'date' => $today->copy()->subDays(16), 'notes' => 'In the beginning God created... The foundation of everything.'],

            ['book_id' => 40, 'chapter' => 7, 'passage_text' => 'Matthew 7', 'date' => $today->copy()->subDays(17), 'notes' => 'Ask and it will be given. Do not judge. Build on the rock.'],
            ['book_id' => 46, 'chapter' => 13, 'passage_text' => '1 Corinthians 13', 'date' => $today->copy()->subDays(17), 'notes' => 'Love chapter. Though I speak with tongues of angels...'],

            ['book_id' => 43, 'chapter' => 2, 'passage_text' => 'John 2', 'date' => $today->copy()->subDays(18), 'notes' => 'Wedding at Cana - Jesus\' first miracle. Water to wine!'],

            ['book_id' => 43, 'chapter' => 3, 'passage_text' => 'John 3', 'date' => $today->copy()->subDays(19), 'notes' => 'Nicodemus and being born again. John 3:16 - the Gospel in a nutshell.'],
            ['book_id' => 19, 'chapter' => 2, 'passage_text' => 'Psalms 2', 'date' => $today->copy()->subDays(19), 'notes' => 'Why do the nations rage? The Lord and His anointed.'],
            ['book_id' => 20, 'chapter' => 31, 'passage_text' => 'Proverbs 31', 'date' => $today->copy()->subDays(19), 'notes' => 'The virtuous woman. Her worth is far above rubies.'],

            ['book_id' => 47, 'chapter' => 5, 'passage_text' => '2 Corinthians 5', 'date' => $today->copy()->subDays(20), 'notes' => 'We are ambassadors for Christ. New creation in Him.'],
            ['book_id' => 51, 'chapter' => 2, 'passage_text' => 'Colossians 2', 'date' => $today->copy()->subDays(20), 'notes' => 'Rooted and built up in Him. Beware of philosophy and empty deceit.'],

            ['book_id' => 52, 'chapter' => 4, 'passage_text' => '1 Thessalonians 4', 'date' => $today->copy()->subDays(21), 'notes' => 'The coming of the Lord. We will be caught up together.'],
            ['book_id' => 56, 'chapter' => 3, 'passage_text' => 'Titus 3', 'date' => $today->copy()->subDays(21), 'notes' => 'He saved us through the washing of regeneration.'],
            ['book_id' => 60, 'chapter' => 1, 'passage_text' => '1 Peter 1', 'date' => $today->copy()->subDays(21), 'notes' => 'Born again to a living hope through the resurrection.'],
            ['book_id' => 62, 'chapter' => 1, 'passage_text' => '1 John 1', 'date' => $today->copy()->subDays(21), 'notes' => 'That which was from the beginning. Fellowship with the Father.'],

            // Week 4 (Days 22-28) - 19 readings
            ['book_id' => 66, 'chapter' => 1, 'passage_text' => 'Revelation 1', 'date' => $today->copy()->subDays(22), 'notes' => 'The revelation of Jesus Christ. Alpha and Omega.'],
            ['book_id' => 40, 'chapter' => 1, 'passage_text' => 'Matthew 1', 'date' => $today->copy()->subDays(22), 'notes' => 'The genealogy of Jesus. God fulfills His promises.'],

            ['book_id' => 41, 'chapter' => 1, 'passage_text' => 'Mark 1', 'date' => $today->copy()->subDays(23), 'notes' => 'The beginning of the gospel of Jesus Christ. John baptizes Jesus.'],
            ['book_id' => 42, 'chapter' => 1, 'passage_text' => 'Luke 1', 'date' => $today->copy()->subDays(23), 'notes' => 'The announcement to Mary. Nothing is impossible with God.'],
            ['book_id' => 43, 'chapter' => 1, 'passage_text' => 'John 1 (re-read)', 'date' => $today->copy()->subDays(23), 'notes' => 'In the beginning was the Word. Second reading, more insights.'],

            ['book_id' => 45, 'chapter' => 1, 'passage_text' => 'Romans 1', 'date' => $today->copy()->subDays(24), 'notes' => 'The gospel is the power of God for salvation.'],
            ['book_id' => 46, 'chapter' => 1, 'passage_text' => '1 Corinthians 1', 'date' => $today->copy()->subDays(24), 'notes' => 'The foolishness of God is wiser than men.'],

            ['book_id' => 47, 'chapter' => 1, 'passage_text' => '2 Corinthians 1', 'date' => $today->copy()->subDays(25), 'notes' => 'The God of all comfort comforts us in our afflictions.'],
            ['book_id' => 48, 'chapter' => 1, 'passage_text' => 'Galatians 1', 'date' => $today->copy()->subDays(25), 'notes' => 'Grace and peace from God our Father and the Lord Jesus Christ.'],
            ['book_id' => 49, 'chapter' => 1, 'passage_text' => 'Ephesians 1', 'date' => $today->copy()->subDays(25), 'notes' => 'Every spiritual blessing in the heavenly places in Christ.'],

            ['book_id' => 50, 'chapter' => 1, 'passage_text' => 'Philippians 1', 'date' => $today->copy()->subDays(26), 'notes' => 'To live is Christ and to die is gain.'],
            ['book_id' => 51, 'chapter' => 1, 'passage_text' => 'Colossians 1', 'date' => $today->copy()->subDays(26), 'notes' => 'He is the image of the invisible God, the firstborn of all creation.'],

            ['book_id' => 52, 'chapter' => 1, 'passage_text' => '1 Thessalonians 1', 'date' => $today->copy()->subDays(27), 'notes' => 'Your work of faith and labor of love and steadfastness of hope.'],
            ['book_id' => 53, 'chapter' => 1, 'passage_text' => '2 Thessalonians 1', 'date' => $today->copy()->subDays(27), 'notes' => 'Grace to you and peace from God our Father.'],
            ['book_id' => 54, 'chapter' => 1, 'passage_text' => '1 Timothy 1', 'date' => $today->copy()->subDays(27), 'notes' => 'Christ Jesus came into the world to save sinners.'],

            ['book_id' => 55, 'chapter' => 1, 'passage_text' => '2 Timothy 1', 'date' => $today->copy()->subDays(28), 'notes' => 'I am not ashamed of the gospel.'],
            ['book_id' => 56, 'chapter' => 1, 'passage_text' => 'Titus 1', 'date' => $today->copy()->subDays(28), 'notes' => 'Paul, a servant of God and an apostle of Jesus Christ.'],
            ['book_id' => 57, 'chapter' => 1, 'passage_text' => 'Philemon 1', 'date' => $today->copy()->subDays(28), 'notes' => 'Grace to you and peace from God our Father.'],
            ['book_id' => 58, 'chapter' => 1, 'passage_text' => 'Hebrews 1', 'date' => $today->copy()->subDays(28), 'notes' => 'God has spoken to us by His Son.'],

            // Week 5 (Days 29-35) - 15 readings
            ['book_id' => 59, 'chapter' => 1, 'passage_text' => 'James 1', 'date' => $today->copy()->subDays(29), 'notes' => 'Count it all joy when you meet trials of various kinds.'],
            ['book_id' => 60, 'chapter' => 2, 'passage_text' => '1 Peter 2', 'date' => $today->copy()->subDays(29), 'notes' => 'Like living stones be built up as a spiritual house.'],

            ['book_id' => 61, 'chapter' => 1, 'passage_text' => '2 Peter 1', 'date' => $today->copy()->subDays(30), 'notes' => 'His divine power has granted to us all things.'],
            ['book_id' => 62, 'chapter' => 2, 'passage_text' => '1 John 2', 'date' => $today->copy()->subDays(30), 'notes' => 'He is the propitiation for our sins.'],
            ['book_id' => 63, 'chapter' => 1, 'passage_text' => '2 John 1', 'date' => $today->copy()->subDays(30), 'notes' => 'Grace, mercy, and peace will be with us.'],

            ['book_id' => 64, 'chapter' => 1, 'passage_text' => '3 John 1', 'date' => $today->copy()->subDays(31), 'notes' => 'Beloved, I pray that you may prosper in all things.'],
            ['book_id' => 65, 'chapter' => 1, 'passage_text' => 'Jude 1', 'date' => $today->copy()->subDays(31), 'notes' => 'To him who is able to keep you from stumbling.'],

            ['book_id' => 1, 'chapter' => 2, 'passage_text' => 'Genesis 2', 'date' => $today->copy()->subDays(32), 'notes' => 'The Lord God formed man from the dust of the ground.'],
            ['book_id' => 1, 'chapter' => 3, 'passage_text' => 'Genesis 3', 'date' => $today->copy()->subDays(32), 'notes' => 'The fall of man. The first promise of redemption.'],

            ['book_id' => 2, 'chapter' => 1, 'passage_text' => 'Exodus 1', 'date' => $today->copy()->subDays(33), 'notes' => 'The Israelites in Egypt. A new king who knew not Joseph.'],
            ['book_id' => 2, 'chapter' => 2, 'passage_text' => 'Exodus 2', 'date' => $today->copy()->subDays(33), 'notes' => 'The birth of Moses. God hears the cry of His people.'],

            ['book_id' => 3, 'chapter' => 1, 'passage_text' => 'Leviticus 1', 'date' => $today->copy()->subDays(34), 'notes' => 'The burnt offering. Drawing near to God.'],

            ['book_id' => 4, 'chapter' => 1, 'passage_text' => 'Numbers 1', 'date' => $today->copy()->subDays(35), 'notes' => 'The census of Israel. God numbers His people.'],
            ['book_id' => 5, 'chapter' => 1, 'passage_text' => 'Deuteronomy 1', 'date' => $today->copy()->subDays(35), 'notes' => 'Moses recounts God\'s faithfulness.'],
            ['book_id' => 6, 'chapter' => 1, 'passage_text' => 'Joshua 1', 'date' => $today->copy()->subDays(35), 'notes' => 'Be strong and courageous. The Lord your God is with you.'],

            // Week 6 (Days 36-42) - 15 readings
            ['book_id' => 7, 'chapter' => 1, 'passage_text' => 'Judges 1', 'date' => $today->copy()->subDays(36), 'notes' => 'After Joshua\'s death. Judah goes up to fight.'],
            ['book_id' => 8, 'chapter' => 1, 'passage_text' => 'Ruth 1 (re-read)', 'date' => $today->copy()->subDays(36), 'notes' => 'Where you go I will go. Second reading of this beautiful story.'],

            ['book_id' => 9, 'chapter' => 1, 'passage_text' => '1 Samuel 1', 'date' => $today->copy()->subDays(37), 'notes' => 'Hannah\'s prayer for a son. God remembers her.'],
            ['book_id' => 10, 'chapter' => 1, 'passage_text' => '2 Samuel 1', 'date' => $today->copy()->subDays(37), 'notes' => 'David learns of Saul and Jonathan\'s death.'],

            ['book_id' => 11, 'chapter' => 1, 'passage_text' => '1 Kings 1', 'date' => $today->copy()->subDays(38), 'notes' => 'King David in his old age. Adonijah\'s attempted coup.'],
            ['book_id' => 12, 'chapter' => 1, 'passage_text' => '2 Kings 1', 'date' => $today->copy()->subDays(38), 'notes' => 'Elijah and the messengers of Ahaziah.'],

            ['book_id' => 13, 'chapter' => 1, 'passage_text' => '1 Chronicles 1', 'date' => $today->copy()->subDays(39), 'notes' => 'The genealogies from Adam. God\'s covenant faithfulness.'],
            ['book_id' => 14, 'chapter' => 1, 'passage_text' => '2 Chronicles 1', 'date' => $today->copy()->subDays(39), 'notes' => 'Solomon asks for wisdom. God grants his request.'],

            ['book_id' => 15, 'chapter' => 1, 'passage_text' => 'Ezra 1', 'date' => $today->copy()->subDays(40), 'notes' => 'Cyrus proclaims the return from exile.'],
            ['book_id' => 16, 'chapter' => 1, 'passage_text' => 'Nehemiah 1', 'date' => $today->copy()->subDays(40), 'notes' => 'Nehemiah\'s prayer for Jerusalem.'],

            ['book_id' => 17, 'chapter' => 1, 'passage_text' => 'Esther 1', 'date' => $today->copy()->subDays(41), 'notes' => 'King Ahasuerus\' feast and Queen Vashti\'s refusal.'],

            ['book_id' => 18, 'chapter' => 2, 'passage_text' => 'Job 2', 'date' => $today->copy()->subDays(42), 'notes' => 'Satan afflicts Job with boils. Job\'s wife and friends.'],
            ['book_id' => 19, 'chapter' => 3, 'passage_text' => 'Psalms 3', 'date' => $today->copy()->subDays(42), 'notes' => 'A psalm of David when he fled from Absalom.'],
            ['book_id' => 20, 'chapter' => 1, 'passage_text' => 'Proverbs 1', 'date' => $today->copy()->subDays(42), 'notes' => 'The fear of the Lord is the beginning of knowledge.'],
            ['book_id' => 21, 'chapter' => 1, 'passage_text' => 'Ecclesiastes 1', 'date' => $today->copy()->subDays(42), 'notes' => 'Vanity of vanities! All is vanity under the sun.'],
        ];

        // Create the reading logs with realistic timestamps
        foreach ($allLogs as $logData) {
            // Calculate realistic created_at timestamp
            // Most people log their reading within a few hours of reading
            // Add some randomness: 0-6 hours after the reading date
            $readingDate = $logData['date'];
            $loggedAt = $readingDate->copy()
                ->addHours($faker->numberBetween(0, 6))
                ->addMinutes($faker->numberBetween(0, 59));

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

        $this->command->info("Created " . count($allLogs) . " test reading logs for {$user->name} (6-week concentrated dataset)");
        $this->command->info("- Week 1 (days 1-7): 18 readings");
        $this->command->info("- Week 2 (days 8-14): 16 readings");
        $this->command->info("- Week 3 (days 15-21): 17 readings");
        $this->command->info("- Week 4 (days 22-28): 19 readings");
        $this->command->info("- Week 5 (days 29-35): 15 readings");
        $this->command->info("- Week 6 (days 36-42): 15 readings");
        $this->command->info("- Total: " . count($allLogs) . " readings over 42 days");
        $this->command->info("- Average: " . round(count($allLogs) / 42, 2) . " readings per day");
    }
}