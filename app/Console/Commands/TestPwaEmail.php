<?php

namespace App\Console\Commands;

use App\Mail\PwaAnnouncement;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestPwaEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pwa-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test PWA announcement email to test locally with Mailpit';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? 'test@example.com';

        // Get or create test user
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => $email,
            ]);
            $this->info("Created test user: {$email}");
        }

        // Send the email
        Mail::to($user->email)->send(new PwaAnnouncement($user));

        $this->info("PWA announcement email sent to: {$user->email}");
        $this->info('Check Mailpit at: http://localhost:8025');

        return Command::SUCCESS;
    }
}
