# Requirements Document

## Introduction

This feature transforms the existing Delight web application backend to support a native mobile app MVP. The mobile app will provide the essential Bible reading habit tracking loop: Read → Log → See Streak → Repeat. The backend exposes a minimal RESTful API focusing on daily reading logs and streak tracking only.

## Requirements

### Requirement 1

**User Story:** As a mobile app user, I want to authenticate securely with the backend, so that I can access my personal reading data and maintain my account across devices.

#### Acceptance Criteria

1. WHEN a user provides valid credentials THEN the system SHALL return an API token using Laravel Sanctum
2. WHEN a user provides invalid credentials THEN the system SHALL return a 401 unauthorized response with error details
3. WHEN a user registers a new account THEN the system SHALL create the account and return an API token
4. WHEN a user logs out THEN the system SHALL revoke the current API token
5. WHEN a user makes API requests with a valid token THEN the system SHALL authenticate the request
6. WHEN a user makes API requests with an invalid or expired token THEN the system SHALL return a 401 unauthorized response

### Requirement 2

**User Story:** As a mobile app user, I want to log my daily Bible reading, so that I can track my progress and maintain my reading streak.

#### Acceptance Criteria

1. WHEN a user submits a reading log entry THEN the system SHALL validate the book and chapter references
2. WHEN a user submits a valid reading log THEN the system SHALL create the reading log entry
3. WHEN a user submits an invalid reading log THEN the system SHALL return validation errors
4. WHEN a user retrieves their reading logs THEN the system SHALL return paginated reading history
5. WHEN a user logs reading for today THEN the system SHALL update their current streak
6. WHEN a user logs reading that fills a gap in their streak THEN the system SHALL recalculate streak with grace period rules

### Requirement 3

**User Story:** As a mobile app user, I want to view my current reading streak, so that I can stay motivated to continue my daily reading habit.

#### Acceptance Criteria

1. WHEN a user requests streak data THEN the system SHALL return current streak and longest streak
2. WHEN streak data is requested THEN the system SHALL use cached data when available for performance
3. WHEN cached streak data is stale THEN the system SHALL recalculate and update the cache

### Requirement 4

**User Story:** As a mobile app user, I want to access Bible reference data, so that I can select books and chapters when logging my reading.

#### Acceptance Criteria

1. WHEN a user requests Bible books list THEN the system SHALL return all books with chapter counts
2. WHEN a user requests chapter count for a book THEN the system SHALL return the correct number of chapters
3. WHEN Bible reference data is requested THEN the system SHALL support both English and French language preferences
4. WHEN invalid book or chapter references are provided THEN the system SHALL return appropriate validation errors

### Requirement 5

**User Story:** As a mobile app developer, I want consistent API response formats and error handling, so that I can efficiently integrate with the backend.

#### Acceptance Criteria

1. WHEN API endpoints are called THEN the system SHALL return consistent JSON response formats
2. WHEN errors occur THEN the system SHALL return standardized error responses with appropriate HTTP status codes
3. WHEN validation fails THEN the system SHALL return detailed field-level error messages

### Requirement 6

**User Story:** As a system administrator, I want the mobile API to maintain data consistency with the web application, so that users have a seamless experience across platforms.

#### Acceptance Criteria

1. WHEN mobile API operations modify data THEN the system SHALL maintain the same business rules as the web application
2. WHEN API responses include streak data THEN the system SHALL use the same caching strategy as the web application
3. WHEN database operations fail THEN the system SHALL rollback transa.
ctions and return appropriate error responses