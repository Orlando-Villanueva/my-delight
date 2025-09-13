<?php

namespace App\Console\Commands;

use App\Mail\PwaAnnouncement;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPwaAnnouncement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:pwa-announcement {--dry-run : Show how many users would receive the email without sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send PWA announcement email to all users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $users = User::all();
        $userCount = $users->count();

        if ($userCount === 0) {
            $this->error('No users found to send emails to.');

            return Command::FAILURE;
        }

        if ($this->option('dry-run')) {
            $this->info("Dry run: Would send PWA announcement to {$userCount} users");

            return Command::SUCCESS;
        }

        if (! $this->confirm("Send PWA announcement to {$userCount} users?")) {
            $this->info('Email sending cancelled.');

            return Command::SUCCESS;
        }

        $this->info("Sending PWA announcement to {$userCount} users...");
        $progressBar = $this->output->createProgressBar($userCount);
        $progressBar->start();

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new PwaAnnouncement($user));
                $sent++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to send to {$user->email}: ".$e->getMessage());
                $failed++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('Email sending complete!');
        $this->info("Sent: {$sent}");

        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return Command::SUCCESS;
    }
}
