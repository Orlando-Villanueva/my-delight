# Requirements Document

## Introduction

This document outlines the requirements for implementing a weekly goal system in Delight, focusing on Phase 1 development. The system will help users track their Bible reading progress on a weekly basis with a default goal of reading 4 times per week. This phase establishes the backend foundation and adds current week progress display to the dashboard, creating a daily engagement driver that answers "What should I do today?"

## Requirements

### Requirement 1

**User Story:** As a Bible reader, I want to see my current week's reading progress on the dashboard, so that I can understand how close I am to achieving my weekly goal and feel motivated to read today.

#### Acceptance Criteria

1. WHEN a user visits the dashboard THEN the system SHALL display their current week's reading progress in the format "X/4 days this week"
2. WHEN a user has read 0 days this week THEN the system SHALL display "0/4 days this week" with appropriate styling to encourage action
3. WHEN a user has read 1-3 days this week THEN the system SHALL display the progress with motivational styling indicating they're making progress
4. WHEN a user has achieved their weekly goal (4+ days) THEN the system SHALL display the progress with celebratory styling and messaging
5. WHEN a new week begins THEN the system SHALL reset the current week progress to 0/4

### Requirement 2

**User Story:** As a Bible reader, I want the system to track my weekly reading data automatically, so that I don't need to manually manage my weekly progress and can focus on reading.

#### Acceptance Criteria

1. WHEN a user logs a reading entry THEN the system SHALL automatically update their current week's reading count
2. WHEN calculating weekly progress THEN the system SHALL count each day with at least one reading entry as 1 day toward the weekly goal
3. WHEN a user logs multiple readings on the same day THEN the system SHALL count it as only 1 day toward the weekly goal
4. WHEN determining the current week THEN the system SHALL use Sunday as the start of the week
5. WHEN reading entries are modified THEN the system SHALL maintain accurate weekly progress calculations

### Requirement 3

**User Story:** As a system administrator, I want the weekly goal data to be properly stored and managed in the database, so that the system can efficiently calculate and display weekly progress.

#### Acceptance Criteria

1. WHEN the system needs to calculate weekly progress THEN it SHALL query reading logs grouped by week efficiently
2. WHEN storing weekly goal configuration THEN the system SHALL use a default goal of 4 readings per week
3. WHEN calculating weekly statistics THEN the system SHALL handle timezone considerations properly
4. WHEN the database is queried for weekly data THEN the system SHALL use appropriate indexes for performance
5. WHEN weekly calculations are performed THEN the system SHALL cache results to improve dashboard performance
6. WHEN a user logs a new reading entry THEN the system SHALL refresh the cached weekly goal statistics
7. WHEN a new week begins THEN the system SHALL invalidate and refresh the cached weekly goal statistics

### Requirement 4

**User Story:** As a Bible reader, I want the weekly progress to integrate seamlessly with the existing dashboard, so that I can see all my reading metrics in one place without confusion.

#### Acceptance Criteria

1. WHEN viewing the dashboard THEN the weekly progress SHALL be prominently displayed alongside existing metrics
2. WHEN the weekly progress is shown THEN it SHALL not interfere with or overshadow the existing daily streak functionality
3. WHEN displaying multiple progress metrics THEN the system SHALL maintain a clear visual hierarchy with weekly progress as primary
4. WHEN the dashboard loads THEN the weekly progress SHALL load efficiently without impacting page performance
5. WHEN viewing on mobile devices THEN the weekly progress SHALL be clearly visible and properly formatted

### Requirement 5

**User Story:** As a developer, I want a dedicated service class to handle weekly goal calculations, so that the business logic is properly separated and maintainable.

#### Acceptance Criteria

1. WHEN weekly calculations are needed THEN the system SHALL use a dedicated WeeklyGoalService class
2. WHEN the WeeklyGoalService calculates progress THEN it SHALL return consistent data structures for UI consumption
3. WHEN integrating with existing services THEN the WeeklyGoalService SHALL not duplicate logic from ReadingLogService
4. WHEN errors occur in weekly calculations THEN the service SHALL handle them gracefully and provide fallback values
5. WHEN the service is tested THEN it SHALL have comprehensive unit tests covering all calculation scenarios

### Requirement 6

**User Story:** As a Bible reader, I want the weekly progress to provide motivational messaging, so that I feel encouraged to continue reading and achieve my weekly goal.

#### Acceptance Criteria

1. WHEN a user has 0 readings this week THEN the system SHALL display encouraging messaging like "Fresh start this week!"
2. WHEN a user is halfway to their goal THEN the system SHALL display progress-focused messaging like "Halfway there!"
3. WHEN a user needs 1 more reading to reach their goal THEN the system SHALL display urgent but positive messaging like "One more to win the week!"
4. WHEN a user achieves their weekly goal THEN the system SHALL display celebratory messaging with appropriate visual feedback
5. WHEN displaying motivational messages THEN the system SHALL maintain a positive, encouraging tone throughout

### Requirement 7

**User Story:** As a Bible reader, I want to understand why the 4-times-per-week goal is recommended, so that I feel confident in the target and motivated by its research foundation.

#### Acceptance Criteria

1. WHEN viewing the weekly progress section THEN the system SHALL include an unobtrusive info icon (ℹ️) next to the weekly goal
2. WHEN a user hovers over or clicks the info icon THEN the system SHALL display a tooltip or popover with the message "Research shows reading 4+ times per week creates meaningful spiritual impact compared to less frequent reading."
3. WHEN the contextual help is displayed THEN it SHALL not interfere with the main dashboard functionality
4. WHEN implementing the info icon THEN it SHALL be optional for users to view and easily dismissible
5. IF tooltip functionality is too complex THEN the system SHALL display subtle gray text under the weekly progress stating "Research-backed weekly target"