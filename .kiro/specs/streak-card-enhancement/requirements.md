# Requirements Document

## Introduction

This feature enhances the reading streak cards to provide better visual states and more psychologically effective motivational messaging. The goal is to encourage users to read their Bible and maintain their reading streaks through improved UI states and contextual messaging that adapts based on the user's current streak status.

## Requirements

### Requirement 1

**User Story:** As a user with no current streak (0 days), I want to see an inactive/neutral state that encourages me to start reading, so that I feel motivated to begin my reading journey without feeling discouraged.

#### Acceptance Criteria

1. WHEN the current streak is 0 days THEN the system SHALL display a neutral background color instead of the active blue
2. WHEN the current streak is 0 days THEN the system SHALL NOT display the fire icon
3. WHEN the current streak is 0 days THEN the system SHALL display "day" (singular) instead of "days" (plural)
4. WHEN the current streak is 0 days THEN the system SHALL display an encouraging message like "Start your reading journey today!" or "Begin building your streak!"

### Requirement 2

**User Story:** As a user with a 1-day streak, I want to see proper singular grammar and encouraging messaging, so that I feel motivated to continue and build momentum.

#### Acceptance Criteria

1. WHEN the current streak is 1 day THEN the system SHALL display "day" (singular) instead of "days" (plural)
2. WHEN the current streak is 1 day THEN the system SHALL display the fire icon to indicate an active streak
3. WHEN the current streak is 1 day THEN the system SHALL display motivational messaging that encourages continuation like "Great start! Keep it going!" or "You're building momentum!"

### Requirement 3

**User Story:** As a user with a multi-day streak, I want to see contextual motivational messages that adapt to my streak length, so that I stay motivated to continue my reading habit.

#### Acceptance Criteria

1. WHEN the current streak is 2-6 days THEN the system SHALL display messages focused on building the habit like "You're building a great habit!" or "Keep the momentum going!"
2. WHEN the current streak is 7-13 days THEN the system SHALL display messages celebrating the weekly milestone like "A full week of reading!" or "You're on fire!"
3. WHEN the current streak is 14-29 days THEN the system SHALL display messages celebrating consistency like "Two weeks strong!"
4. WHEN the current streak is 30+ days THEN the system SHALL display messages celebrating the achievement like "A month of dedication!" or "You're unstoppable!"

### Requirement 4

**User Story:** As a user who has lost their streak but had a previous longest streak, I want to see messaging that acknowledges my past achievement while encouraging me to restart, so that I don't feel completely discouraged.

#### Acceptance Criteria

1. WHEN the current streak is 0 AND the longest streak is greater than 0 THEN the system SHALL display messaging that references the past achievement like "You've done it before, you can do it again!" or "Ready to beat your record of X days?"
2. WHEN the current streak is less than the longest streak THEN the system SHALL optionally display progress toward beating the record like "X days to beat your record!"

### Requirement 5

**User Story:** As a user, I want the streak card visual design to clearly differentiate between active and inactive states, so that I can immediately understand my current status.

#### Acceptance Criteria

1. WHEN the current streak is 0 THEN the system SHALL use a neutral background color (gray or muted tone)
2. WHEN the current streak is greater than 0 THEN the system SHALL use the active blue background color
3. WHEN the current streak is 0 THEN the system SHALL NOT display the fire icon
4. WHEN the current streak is greater than 0 THEN the system SHALL display the fire icon
5. WHEN displaying the streak number THEN the system SHALL maintain the large, prominent number display for easy reading

### Requirement 6

**User Story:** As a user who hasn't read today and is at risk of losing my streak, I want to see a warning state that urgently but encouragingly reminds me to read, so that I don't accidentally break my streak.

#### Acceptance Criteria

1. WHEN the user has a current streak greater than 0 AND has not read today AND it's past a certain time threshold (e.g., 6 PM) THEN the system SHALL display a warning state
2. WHEN in warning state THEN the system SHALL use a warning background color (orange/amber) instead of blue
3. WHEN in warning state THEN the system SHALL display urgent but encouraging messaging like "Don't break your streak! Read today!" or "Your X-day streak needs you!"
4. WHEN in warning state THEN the system SHALL maintain the fire icon but MAY change its color to match the warning theme
5. WHEN in warning state THEN the system SHALL emphasize the time-sensitive nature without being overly stressful

### Requirement 7

**User Story:** As a user, I want the motivational messages to be psychologically effective and varied, so that I don't become desensitized to the same message and stay motivated over time.

#### Acceptance Criteria

1. WHEN displaying motivational messages THEN the system SHALL rotate between multiple message options for each streak range
2. WHEN selecting motivational messages THEN the system SHALL prioritize positive, encouraging language over neutral statements
3. WHEN displaying messages THEN the system SHALL use action-oriented language that encourages the next reading session
4. WHEN the user has read today THEN the system MAY display different messaging acknowledging today's reading