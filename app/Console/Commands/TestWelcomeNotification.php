<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;

class TestWelcomeNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:welcome-notification {--email= : Email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test welcome notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        
        if (!$email) {
            $email = $this->ask('Enter email address to send test welcome notification to');
        }

        // Create a temporary user object for testing
        $testUser = new User([
            'name' => 'Test User',
            'email' => $email,
        ]);

        $this->info("Sending welcome notification to: {$email}");

        try {
            $testUser->notify(new WelcomeNotification());
            $this->info('✅ Welcome notification sent successfully!');
            $this->info('Check Mailpit at http://localhost:8025 to view the email.');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send notification: ' . $e->getMessage());
        }
    }
}
