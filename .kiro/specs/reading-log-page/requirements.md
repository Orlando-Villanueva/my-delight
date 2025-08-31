# Requirements Document

## Introduction

Convert the existing reading log modal into a dedicated page that integrates with the current navigation system. This will provide users with a more focused experience for logging their Bible reading while maintaining the same functionality and form content.

## Requirements

### Requirement 1

**User Story:** As a user, I want to access the reading log form through a dedicated navigation item on desktop, so that I can easily navigate to log my reading without opening a modal.

#### Acceptance Criteria

1. WHEN I am on desktop THEN the sidebar SHALL display a third navigation item labeled "Log Reading"
2. WHEN I click the "Log Reading" navigation item THEN the system SHALL load the reading log page using HTMX content swapping
3. WHEN the reading log page is active THEN the navigation item SHALL be highlighted with the same styling as Dashboard and History
4. WHEN I navigate to the reading log page THEN the page title SHALL update to "Log Reading" with appropriate subtitle

### Requirement 2

**User Story:** As a mobile user, I want to continue using the floating action button to access the reading log, so that I maintain the same convenient mobile experience.

#### Acceptance Criteria

1. WHEN I am on mobile THEN the floating action button SHALL remain visible and functional
2. WHEN I tap the floating action button THEN the system SHALL navigate to the reading log page using HTMX
3. WHEN on mobile THEN there SHALL NOT be a "Log Reading" item in the bottom navigation bar
4. WHEN the reading log page is active on mobile THEN the floating action button SHALL become hidden

### Requirement 3

**User Story:** As a user, I want the reading log form to have identical functionality to the current modal, so that I don't lose any existing features when it becomes a page.

#### Acceptance Criteria

1. WHEN I access the reading log page THEN the form SHALL contain the same date selection options (today/yesterday with grace period logic)
2. WHEN I access the reading log page THEN the form SHALL contain the same Bible book selection with Old/New Testament grouping
3. WHEN I access the reading log page THEN the form SHALL contain the same chapter input field with range support
4. WHEN I access the reading log page THEN the form SHALL contain the same optional notes textarea with character counter
5. WHEN I submit the form THEN the validation SHALL work identically to the current modal implementation

### Requirement 4

**User Story:** As a user, I want to stay on the reading log page after successful submission with a clear success message, so that I can easily log additional readings if needed.

#### Acceptance Criteria

1. WHEN I successfully submit a reading log THEN the system SHALL display a dismissable success message using existing error message styling
2. WHEN I successfully submit a reading log THEN the form SHALL be reset to default values for easy re-use
3. WHEN I successfully submit a reading log THEN I SHALL remain on the reading log page instead of being redirected
4. WHEN a reading log is successfully added THEN the dashboard content SHALL be updated via HTMX triggers (maintaining existing behavior)
5. WHEN I see a success message THEN I SHALL be able to dismiss it by clicking a close button
6. WHEN I successfully submit a reading log THEN any draft form data SHALL be cleared

### Requirement 5

**User Story:** As a user, I want the existing header "Log Reading" button to navigate to the new page instead of opening a modal, so that the navigation behavior is consistent.

#### Acceptance Criteria

1. WHEN I click the "Log Reading" button in the desktop header THEN the system SHALL navigate to the reading log page using HTMX
2. WHEN I click the "Log Reading" button THEN the system SHALL NOT open a modal
3. WHEN the reading log page is active THEN the header button SHALL remain visible and functional
4. WHEN I click the header "Log Reading" button while already on the reading log page THEN the page SHALL refresh/reload the form

### Requirement 6

**User Story:** As a user, I want the reading log page to follow the same layout and styling patterns as Dashboard and History pages, so that the experience feels consistent.

#### Acceptance Criteria

1. WHEN I access the reading log page THEN it SHALL use the authenticated layout template
2. WHEN I access the reading log page THEN it SHALL follow the same content structure as Dashboard and History pages
3. WHEN I access the reading log page THEN the styling SHALL be consistent with the existing design system
4. WHEN I access the reading log page THEN the page SHALL be responsive and work well on all device sizes

### Requirement 7

**User Story:** As a user, I want the reading log page to have proper URL routing and browser history support, so that I can use browser navigation and access the page directly.

#### Acceptance Criteria

1. WHEN I navigate to the reading log page THEN the URL SHALL update to reflect the current location at `/logs/create`
2. WHEN I use browser back/forward buttons THEN the navigation SHALL work seamlessly with HTMX routing
3. WHEN I access `/logs/create` directly THEN I SHALL be authenticated using the same middleware as Dashboard and History pages
4. WHEN I bookmark the reading log page URL THEN I SHALL be able to access it directly (with proper authentication)

### Requirement 8

**User Story:** As a user, I want my form input to be preserved when I navigate between pages, so that I don't lose my work if I accidentally navigate away.

#### Acceptance Criteria

1. WHEN I start filling out the reading log form THEN my input SHALL be saved as draft data
2. WHEN I navigate away from the reading log page THEN my draft data SHALL be preserved
3. WHEN I return to the reading log page THEN my draft data SHALL be restored in the form
4. WHEN I successfully submit the form THEN my draft data SHALL be cleared
5. WHEN I reload the page THEN my draft data SHALL be cleared (fresh start)