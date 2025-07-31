# Design Document

## Overview

Implement essential email notifications for the Delight application by building upon the existing password reset email infrastructure. The system will send welcome emails to new users and legal notification emails for policy changes using Laravel's notification system with queuing and Mailgun integration.

## Architecture

### Current State Analysis
- **Existing Infrastructure**: Password reset emails working with `CustomResetPasswordNotification` and `PasswordResetMail`
- **Email Templates**: Branded email template at `resources/views/emails/password-reset.blade.php`
- **Queue System**: Database queue configured (`QUEUE_CONNECTION=database`)
- **Mail Configuration**: Mailgun configured for production, SMTP for local development
- **Testing**: `TestEmailConfiguration` command for testing email delivery

### Target State
- **Welcome Notifications**: Automatic welcome emails for new user registrations
- **Legal Notifications**: System for notifying users of Terms of Service and Privacy Policy changes
- **Consistent Templates**: Reusable base email template for all notification types
- **Queue Processing**: All emails processed through existing database queue system
- **Error Handling**: Robust error logging and retry mechanisms

## Components and Interfaces

### Notification Classes
Following Laravel's notification pattern, similar to existing `CustomResetPasswordNotification`:

#### WelcomeNotification
```php
class WelcomeNotification extends Notification
{
    use Queueable;
    
    public function via($notifiable): array
    {
        return ['mail'];
    }
    
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Delight!')
            ->view('emails.welcome', [
                'user' => $notifiable,
            ]);
    }
}
```

#### LegalUpdateNotification
```php
class LegalUpdateNotification extends Notification
{
    use Queueable;
    
    public function __construct(
        public string $documentType, // 'terms' or 'privacy'
        public string $summary,
        public string $effectiveDate
    ) {}
    
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Important Update: {$this->documentType}")
            ->view('emails.legal-update', [
                'user' => $notifiable,
                'documentType' => $this->documentType,
                'summary' => $this->summary,
                'effectiveDate' => $this->effectiveDate,
            ]);
    }
}
```

### Email Templates

#### Base Template Structure
Create a reusable base template `resources/views/emails/layouts/base.blade.php` that extracts common elements from the existing password reset template:
- Header with Delight branding
- Consistent styling and responsive design
- Footer with contact information
- Security and branding elements

#### Welcome Email Template
`resources/views/emails/welcome.blade.php`:
- Personalized greeting with user's name
- Getting started tips and key features overview
- Links to important resources (dashboard, help)
- Consistent with existing brand styling

#### Legal Update Email Template
`resources/views/emails/legal-update.blade.php`:
- Clear subject line indicating the type of update
- Summary of key changes
- Link to full updated policy
- Effective date information
- 30-day notice compliance

### User Registration Integration

#### Registration Event Listener
```php
class SendWelcomeNotification
{
    public function handle(Registered $event): void
    {
        $event->user->notify(new WelcomeNotification());
    }
}
```

Register in `EventServiceProvider`:
```php
protected $listen = [
    Registered::class => [
        SendWelcomeNotification::class,
    ],
];
```

### Legal Update System

#### Artisan Command for Legal Updates
```php
class SendLegalUpdateCommand extends Command
{
    protected $signature = 'legal:notify {type} {summary} {effective-date}';
    
    public function handle(): int
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $user->notify(new LegalUpdateNotification(
                $this->argument('type'),
                $this->argument('summary'),
                $this->argument('effective-date')
            ));
        }
        
        return 0;
    }
}
```

## Data Models

### No New Models Required
- Use existing `User` model for notifications
- Leverage Laravel's built-in notification system
- Queue jobs stored in existing `jobs` table

### Queue Configuration
- Continue using existing database queue (`QUEUE_CONNECTION=database`)
- Emails will be queued automatically due to `Queueable` trait
- Existing queue worker setup handles processing

## Error Handling

### Notification Failure Handling
```php
// In notification classes
public function failed(Exception $exception): void
{
    Log::error('Email notification failed', [
        'notification' => static::class,
        'exception' => $exception->getMessage(),
        'user_id' => $this->user_id ?? null,
    ]);
}
```

### Queue Job Retry Configuration
- Use Laravel's default retry mechanism (3 attempts)
- Exponential backoff for failed jobs
- Failed jobs logged to `failed_jobs` table

### Email Service Monitoring
Extend existing `EmailService` class to include:
- Notification delivery tracking
- Error rate monitoring
- Queue health checks

## Testing Strategy

### Unit Tests
- Test notification classes in isolation
- Verify email content and recipients
- Test error handling scenarios

### Feature Tests
- Test welcome email sent on user registration
- Test legal update notifications
- Test email template rendering

### Integration Tests
- Test with actual Mailgun configuration
- Verify queue processing
- Test email delivery end-to-end

### Testing Commands
Extend existing `TestEmailConfiguration` command:
```php
php artisan email:test --type=welcome --to=test@example.com
php artisan email:test --type=legal --to=test@example.com
```

## Implementation Notes

### Template Consistency
- Extract common styles to base template
- Maintain existing Delight branding
- Ensure responsive design across all templates
- Use consistent color scheme and typography

### Performance Considerations
- All emails queued to avoid blocking user registration
- Batch processing for legal updates to large user bases
- Rate limiting to respect Mailgun sending limits

### Security Considerations
- Validate email addresses before sending
- Sanitize user input in email templates
- Use secure token generation for any email links
- Respect user privacy in email content

### Deployment Considerations
- No additional environment variables required
- Uses existing Mailgun configuration
- Queue workers must be running for email delivery
- Monitor queue processing after deployment

## Future Extensibility

### Notification Preferences (Future)
Design allows for easy addition of:
- User email preference settings
- Opt-out mechanisms for non-essential emails
- Notification frequency controls

### Additional Notification Types (Future)
The foundation supports easy addition of:
- Reading streak milestone notifications
- Book completion celebrations
- Reminder notifications
- Community features notifications

### Analytics Integration (Future)
Structure allows for future addition of:
- Email open tracking
- Click-through rate monitoring
- Delivery success metrics
- User engagement analytics