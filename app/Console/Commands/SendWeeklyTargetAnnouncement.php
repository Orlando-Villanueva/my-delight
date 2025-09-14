<?php

namespace App\Console\Commands;

use App\Mail\WeeklyTargetAnnouncementMail;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyTargetAnnouncement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-weekly-target-announcement {--dry-run : Show what would be sent without actually sending} {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly target announcement email to all users';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService): int
    {
        $users = User::whereNotNull('email')->get();
        $userCount = $users->count();

        if ($userCount === 0) {
            $this->info('No users with email addresses found to send emails to.');

            return self::SUCCESS;
        }

        $this->info("Found {$userCount} users to send announcements to:");

        if ($this->option('dry-run')) {
            $this->warn('🧪 DRY RUN MODE - No emails will be sent');

            $this->table(
                ['ID', 'Name', 'Email'],
                $users->take(5)->map(function ($user) {
                    return [$user->id, $user->name, $user->email];
                })->toArray()
            );

            if ($userCount > 5) {
                $this->info('... and '.($userCount - 5).' more users');
            }

            return self::SUCCESS;
        }

        // Safety confirmation unless --force is used
        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to send the weekly target announcement to {$userCount} users?")) {
                $this->info('Cancelled.');

                return self::SUCCESS;
            }
        }

        $this->info('🚀 Starting to send weekly target announcements...');

        $bar = $this->output->createProgressBar($userCount);
        $bar->start();

        $sentCount = 0;
        $failedCount = 0;
        $failedUsers = [];

        foreach ($users as $user) {
            $success = $emailService->sendWithErrorHandling(
                function () use ($user) {
                    Mail::to($user->email)->send(new WeeklyTargetAnnouncementMail);
                },
                'weekly-target-announcement'
            );

            if ($success) {
                $sentCount++;
            } else {
                $failedCount++;
                $failedUsers[] = $user->email;
            }

            $bar->advance();

            // Small delay to avoid overwhelming the mail server
            usleep(100000); // 100ms delay
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('📧 Announcement sending completed!');
        $this->info("✅ Successfully sent: {$sentCount}");

        if ($failedCount > 0) {
            $this->error("❌ Failed to send: {$failedCount}");
            $this->error('Failed emails: '.implode(', ', $failedUsers));
            $this->info('Check the logs for detailed error information.');
        }

        return $failedCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}
