# Design Document

## Overview

Complete the email setup for Delight's MVP launch by configuring Mailgun for production email delivery. The existing password reset functionality is already implemented and working locally - we just need to configure production email service and ensure proper environment management.

## Architecture

### Current State
- Password reset emails work locally using Mailpit (SMTP on port 1025)
- Custom `PasswordResetMail` mailable class exists with branded template
- Custom `CustomResetPasswordNotification` notification is implemented
- User model is configured to use the custom notification
- Mailgun package (`symfony/mailgun-mailer`) is already installed

### Target State
- Production uses Mailgun for reliable email delivery
- Environment-specific configuration automatically selects correct mail driver
- Basic error logging for email failures
- Simple production email testing capability

## Components and Interfaces

### Email Configuration
- **Mail Driver Selection**: Environment-based configuration in `config/mail.php`
- **Mailgun Configuration**: Production environment variables for Mailgun API
- **Local Development**: Continue using existing Mailpit setup

### Environment Variables
```
# Production
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-secret-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME=Delight

# Local Development (existing)
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
```

### Error Handling
- **Email Failures**: Log to Laravel's default logging system
- **Missing Configuration**: Clear error messages for missing environment variables
- **Graceful Degradation**: System continues to function even if email fails

## Data Models

No new data models required. Existing models are sufficient:
- `User` model with `sendPasswordResetNotification()` method
- Password reset tokens handled by Laravel's built-in system

## Error Handling

### Email Delivery Failures
- Log email failures with recipient and error details
- Don't expose email service errors to end users
- Provide generic "email sent" message regardless of actual delivery status

### Configuration Errors
- Validate required environment variables on application boot
- Provide clear error messages for missing Mailgun configuration
- Fail gracefully with informative logs

## Testing Strategy

### Production Email Testing
- Simple Artisan command to send test email
- Verify Mailgun configuration without affecting users
- Test email template rendering and delivery

### Automated Testing
- Use existing array mail driver for tests
- Verify email notifications are queued/sent in feature tests
- Test password reset flow end-to-end

## Implementation Notes

### Mailgun Setup Requirements
1. Mailgun account with verified domain
2. API key and domain configuration
3. DNS records configured for domain verification
4. Laravel Forge environment variable configuration

### Laravel Forge Configuration
- Set environment variables in Forge dashboard
- Ensure mail queue processing if using queued emails
- Configure proper from address with verified domain

### Deployment Considerations
- Environment variables must be set before deployment
- Test email delivery immediately after production deployment
- Monitor logs for email delivery issues post-launch