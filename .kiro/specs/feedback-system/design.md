# Design Document

## Overview

The feedback system will provide authenticated users with a streamlined way to submit bug reports, feature requests, general feedback, and UI/UX issues directly from within the Delight application. The system follows the existing application patterns using Laravel backend with HTMX for dynamic interactions and Alpine.js for client-side behavior. Feedback will be sent via email to administrators using Laravel's mail system.

## Architecture

### High-Level Flow
1. User clicks feedback button in navigation
2. HTMX loads dedicated feedback page (similar to dashboard/history navigation)
3. User fills form with feedback type and description
4. System auto-collects technical context (URL, browser info, user data)
5. Form submission sends email to administrators
6. User receives success confirmation with "Back to Dashboard" and "Submit More Feedback" buttons

### Technology Stack Integration
- **Backend**: Laravel controller following existing patterns
- **Frontend**: HTMX for form submission and modal loading
- **Styling**: Tailwind CSS matching existing design system
- **Email**: Laravel Mail with Mailable class
- **Validation**: Laravel form validation with error handling
- **JavaScript**: Alpine.js for modal behavior and form interactions

## Components and Interfaces

### 1. FeedbackController
**Purpose**: Handle feedback form display and submission
**Location**: `app/Http/Controllers/FeedbackController.php`

**Methods**:
- `index()`: Return feedback page view for HTMX navigation
- `store()`: Process form submission, send email, return success/error response

**Follows existing patterns from navigation controllers**:
- Returns full page content for HTMX requests
- Uses validation with error handling
- Integrates with existing navigation system

### 2. FeedbackMail
**Purpose**: Format and send feedback emails to administrators
**Location**: `app/Mail/FeedbackMail.php`

**Properties**:
- Feedback data (type, description, user info)
- Technical context (URL, browser info, timestamp)
- Email template with structured layout

**Follows existing mail patterns from WeeklyTargetAnnouncementMail**

### 3. FeedbackRequest
**Purpose**: Validate feedback form input
**Location**: `app/Http/Requests/FeedbackRequest.php`

**Validation Rules**:
- `feedback_type`: required, in allowed values (Bug Report, Feature Request, General Feedback, UI/UX Issue)
- `description`: required, string, max 2000 characters
- Auto-collected fields don't need validation

**Follows existing validation patterns from ReadingLogController**

### 4. Frontend Components

#### Navigation Integration
**Desktop Sidebar**: Add feedback button above "Sign Out" in `resources/views/layouts/authenticated.blade.php`
**Mobile Menu**: Add feedback option above "Sign out" in user dropdown menu

#### Page System
**Use existing HTMX navigation pattern** from dashboard/history:
- Same HTMX page loading via `hx-get` and `hx-target="#page-container"`
- Add `currentView = 'feedback'` state to Alpine.js navigation management  
- Same page transition patterns with active navigation styling
- Feedback button styled as active when `currentView === 'feedback'`

#### Form Component
**Location**: `resources/views/feedback/index.blade.php`
**Features**:
- Full-page feedback form with proper page title
- Feedback type dropdown
- Description textarea with character counter (maxlength="2000", showCounter=true)
- Auto-focus and accessibility features
- Error display matching existing patterns

#### Success/Error Components
**Success**: Success message completely replaces the feedback form, showing confirmation message with "Back to Dashboard" (primary) and "Submit More Feedback" (secondary) buttons
**Success Flow**: HTMX returns success view that replaces entire form content
**Error handling**: Inline with form using existing error display patterns, form remains visible with user input preserved

## Data Models

### Feedback Submission Data Structure
```php
[
    'feedback_type' => 'Bug Report|Feature Request|General Feedback|UI/UX Issue',
    'description' => 'User provided description (max 2000 chars)',
    'user_name' => 'Auto-filled from auth()->user()->name',
    'user_email' => 'Auto-filled from auth()->user()->email',
    'user_id' => 'Auto-filled from auth()->user()->id',
    'current_url' => 'Auto-collected via JavaScript',
    'browser_info' => 'Auto-collected user agent and viewport',
    'timestamp' => 'Auto-generated submission time'
]
```

### Email Template Structure
- **Subject**: `[Delight Feedback] {Type} - {First 50 chars of description}`
- **Body Sections**:
  - Feedback type and description
  - User information
  - Technical context (URL, browser, timestamp)
  - Clear formatting for easy reading

## Error Handling

### Validation Errors
- Display inline with form fields
- Preserve user input on validation failure
- Use existing error styling and patterns
- Provide clear, actionable error messages

### Email Sending Errors
- Log errors for debugging
- Display user-friendly error message
- Keep form open with preserved input
- Provide retry option

### Network/HTMX Errors
- Handle connection failures gracefully
- Show appropriate error states
- Maintain form data integrity

## Testing Strategy

### Unit Tests
- **FeedbackController**: Test form display and submission logic
- **FeedbackMail**: Test email content generation and formatting
- **FeedbackRequest**: Test validation rules and error messages

### Feature Tests
- **Form Display**: Test modal opening and form rendering
- **Form Submission**: Test successful feedback submission flow
- **Email Sending**: Test email generation and sending (using mail fake)
- **Error Handling**: Test validation errors and email failures
- **Navigation Integration**: Test feedback access from both desktop and mobile

### Browser Tests (Optional)
- **Modal Behavior**: Test opening, closing, and focus management
- **Form Interaction**: Test dropdown selection and textarea input
- **Responsive Design**: Test on different screen sizes
- **Accessibility**: Test keyboard navigation and screen reader compatibility

## Privacy Policy Adjustment

### Technical Information Collection for Feedback
The feedback system requires a **minor adjustment** to the current privacy policy to collect minimal technical context for debugging purposes:

**Addition to "Information We Collect" section:**
- **Feedback Technical Context (when submitting feedback only)**: Current page URL, basic browser information (user agent), and submission timestamp to help administrators understand and reproduce reported issues

**Addition to "How We Use Your Information" section:**  
- **Feedback Support**: Technical context is used solely for debugging and improving reported issues, and is only collected when you voluntarily submit feedback

This minimal technical data collection is limited to feedback submissions only and aligns with the app's privacy-focused approach.

## Security Considerations

### Input Validation
- Sanitize all user input
- Validate feedback type against allowed values
- Limit description length to prevent abuse
- Use Laravel's built-in CSRF protection

### Email Security
- Validate email addresses
- Prevent email header injection
- Daily rate limiting: 10 feedback submissions per user per day
- Log all feedback submissions for audit trail

### Authentication
- Ensure only authenticated users can access feedback
- Include user ID in all feedback for accountability
- Auto-populate user information to prevent spoofing

### Privacy Compliance
- Collect minimal technical information only for feedback debugging
- Technical data limited to: page URL, user agent, timestamp
- No persistent tracking or behavioral analytics
- Technical data used solely for issue resolution

## Configuration

### Environment Variables

**Local Development (.env):**
```env
FEEDBACK_ADMIN_EMAIL=feedback@delight.test  # For Mailpit testing
MAIL_FROM_ADDRESS=noreply@delight.test
MAIL_FROM_NAME="Delight Feedback System"
```

**Production (.env.production):**
```env
FEEDBACK_ADMIN_EMAIL=your-personal@email.com  # Set to actual admin email
MAIL_FROM_ADDRESS=noreply@delight.app
MAIL_FROM_NAME="Delight Feedback System"
```

### Route Configuration
```php
// In routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});
```

## Integration Points

### Existing Modal System
- Reuse modal backdrop and slide-over from reading log
- Same Alpine.js modal state management
- Consistent HTMX loading patterns

### Navigation System
- Add feedback button to desktop sidebar navigation
- Add feedback option to mobile user menu
- Maintain existing navigation styling and behavior

### Email System
- Use existing mail configuration
- Follow established mail class patterns
- Integrate with current email templates structure

### Error Handling System
- Use existing validation error display patterns
- Follow established success/error message styling
- Maintain consistent user experience

## Performance Considerations

### Frontend Performance
- Lazy load feedback form via HTMX
- Minimal JavaScript for browser info collection
- Reuse existing modal and form styles

### Backend Performance
- Simple controller logic without database operations
- Efficient email generation and sending
- Minimal server resources required

### Email Performance
- Use Laravel's queue system for email sending (future enhancement)
- Simple email templates for fast generation
- Appropriate email size limits