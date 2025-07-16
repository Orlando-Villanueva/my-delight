# Bible Reading Habit Builder - Requirements Document

## Introduction

The Bible Reading Habit Builder is a Laravel-based web application designed to help users establish and maintain consistent Bible reading habits through a simple "Read → Log → See Progress" loop. The application provides streak tracking, progress visualization, and motivational feedback to encourage daily Bible reading.

**Core Value Proposition:** Help users track and build consistency in their Bible reading habits through simple logging and motivating progress visualization.

## Requirements

### Requirement 1: User Authentication System

**User Story:** As a new user, I want to create an account and securely access my reading data, so that I can track my personal Bible reading journey.

#### Acceptance Criteria

1. WHEN a user visits the registration page THEN the system SHALL display a form with name, email, and password fields
2. WHEN a user submits valid registration data THEN the system SHALL create a new user account using Laravel Fortify
3. WHEN a user submits invalid registration data THEN the system SHALL display appropriate validation errors
4. WHEN a user attempts to register with an existing email THEN the system SHALL prevent duplicate registration
5. WHEN a user logs in with valid credentials THEN the system SHALL authenticate them and redirect to dashboard
6. WHEN a user requests password reset THEN the system SHALL send a reset email (requires email service configuration)
7. WHEN a user logs out THEN the system SHALL terminate their session securely

### Requirement 2: Bible Reading Log Management

**User Story:** As a daily Bible reader, I want to log the chapters I've read each day, so that I can track my reading history and maintain accountability.

#### Acceptance Criteria

1. WHEN a user clicks "Log Reading" THEN the system SHALL display a modal form with Bible book and chapter selection
2. WHEN a user selects a Bible book THEN the system SHALL dynamically populate valid chapter options for that book
3. WHEN a user enters chapter input THEN the system SHALL support both single chapters (e.g., "3") and ranges (e.g., "1-3")
4. WHEN a user submits a reading log THEN the system SHALL validate the Bible reference against the 66 books configuration
5. WHEN a user logs a chapter range THEN the system SHALL create separate reading log entries for each chapter
6. WHEN a user adds notes to a reading THEN the system SHALL store notes with a 1000 character limit
7. WHEN a user attempts to log future dates THEN the system SHALL restrict entries to today or yesterday only
8. WHEN a user logs a reading THEN the system SHALL automatically update their book progress tracking

### Requirement 3: Streak Calculation and Display

**User Story:** As a motivated reader, I want to see my current reading streak and longest streak ever, so that I feel encouraged to maintain consistency.

#### Acceptance Criteria

1. WHEN a user has reading logs THEN the system SHALL calculate their current consecutive reading streak
2. WHEN calculating streaks THEN the system SHALL implement a 1-day grace period (reading today OR yesterday continues streak)
3. WHEN a user's streak is broken THEN the system SHALL reset the current streak to 0
4. WHEN a user achieves a new longest streak THEN the system SHALL update their all-time record
5. WHEN displaying streaks THEN the system SHALL show both current streak and longest streak prominently
6. WHEN a user logs a new reading THEN the system SHALL immediately update streak calculations
7. WHEN calculating streaks THEN the system SHALL normalize dates to start of day to avoid timezone issues

### Requirement 4: Book Progress Tracking

**User Story:** As a systematic reader, I want to see my progress through each book of the Bible, so that I can understand which books I've started and completed.

#### Acceptance Criteria

1. WHEN a user logs a chapter THEN the system SHALL update their progress for that specific Bible book
2. WHEN tracking book progress THEN the system SHALL store which chapters have been read as a JSON array
3. WHEN calculating completion THEN the system SHALL determine percentage based on chapters read vs total chapters
4. WHEN a user completes all chapters in a book THEN the system SHALL mark the book as completed
5. WHEN displaying book progress THEN the system SHALL show all 66 Bible books with color-coded status
6. WHEN showing book status THEN the system SHALL use gray for not started, blue for in progress, green for completed
7. WHEN organizing books THEN the system SHALL provide Old Testament/New Testament toggle functionality
8. WHEN displaying progress THEN the system SHALL show individual book percentages and mini progress bars

### Requirement 5: Reading History and Calendar Visualization

**User Story:** As a visual learner, I want to see a calendar view of my reading history, so that I can identify patterns and gaps in my reading habit.

#### Acceptance Criteria

1. WHEN viewing the dashboard THEN the system SHALL display a GitHub-style calendar heatmap
2. WHEN showing calendar data THEN the system SHALL use 7 intensity levels based on daily reading count
3. WHEN a user hovers over a calendar day THEN the system SHALL show a tooltip with date and chapter count
4. WHEN displaying the calendar THEN the system SHALL highlight today with a ring indicator
5. WHEN viewing reading history THEN the system SHALL provide filtering options (7 days, 30 days, 90 days, all)
6. WHEN showing reading logs THEN the system SHALL group entries by date and deduplicate same-session readings
7. WHEN paginating history THEN the system SHALL paginate by days rather than individual log entries
8. WHEN using HTMX THEN the system SHALL support infinite scroll for reading history

### Requirement 6: Dashboard Statistics and Analytics

**User Story:** As an analytical reader, I want to see comprehensive statistics about my reading habits, so that I can understand my progress and stay motivated.

#### Acceptance Criteria

1. WHEN viewing the dashboard THEN the system SHALL display current and longest streak prominently
2. WHEN showing statistics THEN the system SHALL include total readings, books started, and books completed
3. WHEN calculating progress THEN the system SHALL show overall Bible reading percentage
4. WHEN displaying recent activity THEN the system SHALL show the last 5 unique reading sessions
5. WHEN showing monthly stats THEN the system SHALL calculate reading days for current month and week
6. WHEN presenting data THEN the system SHALL include motivational messaging based on current progress
7. WHEN updating statistics THEN the system SHALL refresh data via HTMX without full page reload

### Requirement 7: Multilingual Support (French)

**User Story:** As a French-speaking user, I want to use the application in French, so that I can engage with the content in my preferred language.

#### Acceptance Criteria

1. WHEN the system loads THEN it SHALL support both English and French locales
2. WHEN displaying Bible books THEN the system SHALL show localized book names (e.g., "Genèse" for Genesis)
3. WHEN switching languages THEN the system SHALL provide seamless language toggle functionality
4. WHEN using French locale THEN all UI elements SHALL be translated appropriately
5. WHEN handling longer French text THEN the responsive design SHALL accommodate text expansion
6. WHEN storing data THEN the system SHALL maintain language-independent Bible book IDs for consistency

### Requirement 8: Performance Optimization and Caching

**User Story:** As a user, I want the application to load quickly and respond smoothly, so that logging my daily reading is efficient and enjoyable.

#### Acceptance Criteria

1. WHEN loading the dashboard THEN the system SHALL complete rendering in under 500ms
2. WHEN calculating statistics THEN the system SHALL implement caching for dashboard data with 5-minute TTL
3. WHEN computing streaks THEN the system SHALL cache current and longest streak calculations
4. WHEN displaying calendar data THEN the system SHALL cache yearly calendar data with 30-minute TTL
5. WHEN a user logs new reading THEN the system SHALL invalidate relevant caches automatically
6. WHEN performing database queries THEN the system SHALL use optimized indexes for date-based lookups
7. WHEN calculating streaks THEN the system SHALL convert PHP processing to SQL window functions where possible

### Requirement 9: Email Service Integration

**User Story:** As a user who forgets passwords, I want to receive password reset emails, so that I can regain access to my account when needed.

#### Acceptance Criteria

1. WHEN configuring email THEN the system SHALL use Postmark for production email delivery
2. WHEN in development THEN the system SHALL use Mailtrap for email testing
3. WHEN a user requests password reset THEN the system SHALL send a properly formatted reset email
4. WHEN sending emails THEN the system SHALL include consistent branding and styling
5. WHEN email delivery fails THEN the system SHALL handle errors gracefully and log issues
6. WHEN testing email flows THEN the system SHALL verify end-to-end password reset functionality

### Requirement 10: HTMX Integration and User Experience

**User Story:** As a modern web user, I want smooth, responsive interactions without full page reloads, so that the application feels fast and native-like.

#### Acceptance Criteria

1. WHEN submitting forms THEN the system SHALL use HTMX for seamless content updates
2. WHEN displaying modals THEN the system SHALL load reading log forms via HTMX
3. WHEN showing validation errors THEN the system SHALL return form partials with error states
4. WHEN updating dashboard THEN the system SHALL refresh statistics without full page reload
5. WHEN navigating between sections THEN the system SHALL use HTMX for content loading
6. WHEN handling form submissions THEN the system SHALL provide immediate visual feedback
7. WHEN loading content THEN the system SHALL include appropriate loading indicators

### Requirement 11: Responsive Design and Mobile Support

**User Story:** As a mobile user, I want to access all features on my smartphone, so that I can log readings anywhere and anytime.

#### Acceptance Criteria

1. WHEN using mobile devices THEN the system SHALL display a bottom navigation with 3 tabs
2. WHEN on mobile THEN the system SHALL show a floating action button for "Log Reading"
3. WHEN using desktop THEN the system SHALL display a sidebar navigation with header action button
4. WHEN interacting on touch devices THEN all touch targets SHALL be minimum 44px x 44px
5. WHEN viewing on different screen sizes THEN the layout SHALL adapt appropriately
6. WHEN using the calendar THEN it SHALL be readable and interactive on both mobile and desktop
7. WHEN accessing forms THEN they SHALL be usable on touch screens with proper keyboard types

### Requirement 12: Data Integrity and Validation

**User Story:** As a user, I want my reading data to be accurate and consistent, so that my progress tracking is reliable and meaningful.

#### Acceptance Criteria

1. WHEN validating Bible references THEN the system SHALL use the comprehensive BibleReferenceService
2. WHEN storing reading logs THEN the system SHALL prevent duplicate entries for the same chapter and date
3. WHEN updating book progress THEN the system SHALL maintain data consistency between reading logs and progress tracking
4. WHEN handling chapter ranges THEN the system SHALL validate against actual book chapter counts
5. WHEN processing user input THEN the system SHALL sanitize and validate all form data
6. WHEN storing dates THEN the system SHALL normalize to user's timezone for accurate tracking
7. WHEN calculating statistics THEN the system SHALL handle edge cases like empty data gracefully