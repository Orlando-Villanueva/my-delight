# Requirements Document

## Introduction

This feature addresses inconsistent time display across the Bible reading tracker application. Currently, the Recent Readings widget on the dashboard shows incorrect "time ago" values by using `created_at` timestamps, while the reading history correctly uses `date_read`. This creates user confusion when the same reading shows different time values in different parts of the application.

## Requirements

### Requirement 1

**User Story:** As a user viewing my recent readings, I want to see consistent and accurate "time ago" information that reflects when I actually read the passage, so that I can trust the timeline information across the application.

#### Acceptance Criteria

1. WHEN a user views the Recent Readings widget on the dashboard THEN the system SHALL display "time ago" based on the `date_read` field
2. WHEN a user views the reading history THEN the system SHALL display "time ago" based on the `date_read` field
3. WHEN the same reading appears in both locations THEN the system SHALL show identical "time ago" values
4. WHEN a reading was logged on a different date than it was read THEN the system SHALL prioritize the actual reading date for time calculations

### Requirement 2

**User Story:** As a developer maintaining the codebase, I want a centralized time formatting service, so that time display logic is consistent and maintainable across all components.

#### Acceptance Criteria

1. WHEN time formatting is needed THEN the system SHALL use a single, centralized service method
2. WHEN calculating "time ago" values THEN the system SHALL use the `date_read` field as the reference point
3. WHEN displaying time information THEN the system SHALL format it consistently (e.g., "1 day ago", "2 hours ago")
4. IF the `date_read` is null or invalid THEN the system SHALL fall back to `created_at` with appropriate handling

### Requirement 3

**User Story:** As a user, I want accurate time representations that make sense in the context of my reading habits, so that I can track my progress effectively.

#### Acceptance Criteria

1. WHEN a reading was done "today" THEN the system SHALL show hours/minutes ago if logged the same day
2. WHEN a reading was done "yesterday" THEN the system SHALL show "1 day ago" regardless of when it was logged
3. WHEN a reading is older than yesterday THEN the system SHALL show the appropriate number of days ago
4. WHEN displaying time ranges THEN the system SHALL use consistent formatting across all components