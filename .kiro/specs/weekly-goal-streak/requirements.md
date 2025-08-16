# Requirements Document - Phase 2: Weekly Streak System

## Introduction

This document outlines the **Phase 2 requirements** for implementing a weekly streak system in Delight. Building on the successful Phase 1 implementation of current week progress tracking, Phase 2 adds weekly streak calculation and display to create a secondary achievement layer that celebrates long-term consistency.

**Phase 2 Focus**: Implement weekly streak tracking where users maintain streaks by achieving their weekly goal (4+ readings) for consecutive weeks. This creates a hierarchy of engagement: Current Week Progress (primary) → Weekly Streak (secondary achievement) → Daily Streak (supporting metric).

**Completed in Phase 1**: Current week progress tracking, WeeklyGoalService foundation, dashboard integration, and basic motivational messaging.

## Requirements

### Requirement 1

**User Story:** As a Bible reader, I want to see my weekly streak count on the dashboard, so that I can celebrate my long-term consistency and feel motivated to maintain my weekly reading habit.

#### Acceptance Criteria

1. WHEN a user visits the dashboard THEN the system SHALL display their current weekly streak count alongside their current week progress
2. WHEN a user has achieved their weekly goal for consecutive weeks THEN the system SHALL display the streak count (e.g., "3 weeks in a row!")
3. WHEN a user has no active weekly streak THEN the system SHALL display encouraging messaging to start a new streak
4. WHEN a user breaks their weekly streak THEN the system SHALL reset the count to 0 and provide motivational messaging to restart
5. WHEN a new week begins and the previous week's goal was achieved THEN the system SHALL increment the weekly streak counter

### Requirement 2

**User Story:** As a Bible reader, I want the weekly streak to be calculated automatically based on my weekly goal achievements, so that I can focus on reading without manually tracking my consistency.

#### Acceptance Criteria

1. WHEN a user achieves their weekly goal (4+ days) THEN the system SHALL count that week toward their weekly streak
2. WHEN a user fails to achieve their weekly goal THEN the system SHALL break their current weekly streak
3. WHEN calculating weekly streaks THEN the system SHALL only count complete weeks (Sunday to Saturday)
4. WHEN a week is in progress THEN the system SHALL not count it toward the streak calculation until the week is complete
5. WHEN determining streak status THEN the system SHALL use the same week boundaries as the weekly goal system (Sunday start)
6. WHEN evaluating weekly streaks THEN the system SHALL only evaluate and update streak counts when a week ends (after Saturday), regardless of mathematical impossibility during the week

### Requirement 3

**User Story:** As a Bible reader, I want the weekly streak to be displayed as a secondary achievement that complements but doesn't overshadow my current week progress, so that I maintain focus on this week's goal while celebrating long-term consistency.

#### Acceptance Criteria

1. WHEN viewing the dashboard THEN the weekly streak SHALL be visually secondary to the current week progress
2. WHEN both metrics are displayed THEN the hierarchy SHALL be: Current Week Progress (primary) → Weekly Streak (secondary) → Daily Streak (supporting)
3. WHEN the weekly streak is shown THEN it SHALL use distinct but complementary styling to the weekly goal card
4. WHEN a user has an active weekly streak THEN it SHALL be prominently displayed with celebratory messaging
5. WHEN a user has no active streak THEN the display SHALL be encouraging rather than discouraging

### Requirement 4

**User Story:** As a developer, I want weekly streak calculations to be handled by the existing WeeklyGoalService, so that all weekly-related logic is centralized and maintainable.

#### Acceptance Criteria

1. WHEN calculating weekly streaks THEN the system SHALL extend the existing WeeklyGoalService class
2. WHEN weekly streak data is needed THEN it SHALL be included in the existing weekly goal data structure
3. WHEN integrating streak calculations THEN the service SHALL reuse existing week boundary logic
4. WHEN errors occur in streak calculations THEN the service SHALL handle them gracefully with fallback values
5. WHEN the service is extended THEN it SHALL maintain backward compatibility with existing weekly goal functionality

### Requirement 5

**User Story:** As a Bible reader, I want motivational messaging that celebrates my weekly streak achievements and encourages me to continue, so that I feel recognized for my consistency and motivated to maintain the habit.

#### Acceptance Criteria

1. WHEN a user has a weekly streak of 1 week THEN the system SHALL display encouraging messaging about starting strong
2. WHEN a user has a weekly streak of 2-3 weeks THEN the system SHALL display progress-focused messaging
3. WHEN a user has a weekly streak of 4+ weeks THEN the system SHALL display celebratory messaging about their consistency
4. WHEN a user's streak is broken THEN the system SHALL provide supportive messaging about restarting
5. WHEN displaying streak messaging THEN it SHALL be positive and encouraging rather than guilt-inducing

### Requirement 6

**User Story:** As a Bible reader, I want the weekly streak to integrate seamlessly with the existing dashboard layout, so that all my reading metrics work together harmoniously without cluttering the interface.

#### Acceptance Criteria

1. WHEN the weekly streak is added THEN it SHALL fit within the existing dashboard grid layout
2. WHEN viewing on mobile devices THEN the weekly streak SHALL be clearly visible and properly formatted
3. WHEN the dashboard loads THEN the weekly streak SHALL load efficiently without impacting performance
4. WHEN HTMX updates occur THEN the weekly streak SHALL be included in partial responses
5. WHEN the layout is responsive THEN the weekly streak SHALL adapt appropriately to different screen sizes

### Requirement 7

**User Story:** As a system administrator, I want weekly streak calculations to be efficient and use existing database structures, so that the feature doesn't impact application performance or require complex infrastructure changes.

#### Acceptance Criteria

1. WHEN calculating weekly streaks THEN the system SHALL use the existing reading_logs table without additional schema changes
2. WHEN querying streak data THEN the system SHALL use efficient database queries with proper date range filtering
3. WHEN streak calculations are performed THEN they SHALL leverage existing indexes on user_id and date_read
4. WHEN multiple users access the dashboard THEN streak calculations SHALL not create performance bottlenecks
5. WHEN the system calculates streaks THEN it SHALL handle edge cases like user registration dates and data gaps gracefully
