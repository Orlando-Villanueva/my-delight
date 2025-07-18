<?php

namespace App\Console\Commands;

use App\Mail\PasswordResetMail;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {--send : Actually send a test email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration and optionally send a test email';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService): int
    {
        $this->info('Testing email configuration...');
        $this->newLine();

        // Test configuration
        $configStatus = $emailService->testEmailConfiguration();
        
        if ($configStatus['success']) {
            $this->info('✅ ' . $configStatus['message']);
        } else {
            $this->error('❌ ' . $configStatus['message']);
            return 1;
        }

        // Show configuration details
        $this->newLine();
        $this->info('Current email configuration:');
        $config = $emailService->getConfigurationStatus();
        
        foreach ($config as $key => $value) {
            $this->line("  {$key}: {$value}");
        }

        // Optionally send test email
        if ($this->option('send')) {
            $this->newLine();
            $this->info('Sending test email...');
            
            $testUrl = url('/test-reset-url');
            
            try {
                Mail::to(config('mail.from.address'))
                    ->send(new PasswordResetMail($testUrl));
                
                $this->info('✅ Test email sent successfully!');
                
                if (config('mail.default') === 'smtp' && config('mail.mailers.smtp.host') === 'localhost') {
                    $this->info('📧 Check Mailpit at http://localhost:8025 to view the email');
                }
            } catch (\Exception $e) {
                $this->error('❌ Failed to send test email: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->newLine();
            $this->info('💡 Use --send flag to actually send a test email');
        }

        return 0;
    }
}
