# Design Document - Phase 2: Weekly Streak System

## Overview

This design document outlines the **Phase 2 technical implementation** for adding weekly streak tracking to the existing weekly goal system in Delight. Building on the solid Phase 1 foundation, this phase adds weekly streak calculation and display as a secondary achievement layer that celebrates long-term consistency.

**Phase 2 Focus**: Extend the existing WeeklyGoalService to calculate weekly streaks, create a complementary UI component for streak display, and establish the proper visual hierarchy: Current Week Progress (primary) → Weekly Streak (secondary) → Daily Streak (supporting).

## Architecture

### Service Layer Extension
Building on the existing Phase 1 architecture:
- **WeeklyGoalService**: Extended with weekly streak calculation methods
- **UserStatisticsService**: Updated to include weekly streak data in dashboard statistics
- **DashboardController**: Already configured to pass weekly data to views

### Weekly Streak Calculation Strategy
Weekly streaks will be calculated using the same direct calculation approach as Phase 1:
- Iterate backwards through complete weeks from the most recent completed week
- Count consecutive weeks where weekly goal (4+ days) was achieved
- Stop counting when a week with <4 days is found or when reaching user registration date
- Use existing `reading_logs` table with efficient date range queries

### Week Boundary Consistency
- Reuse existing week boundary logic from Phase 1 (Sunday start)
- Weekly streaks only count complete weeks (Sunday to Saturday)
- Current week in progress does not affect streak calculation
- Maintains consistency with existing weekly goal system

## Components and Interfaces

### Extended WeeklyGoalService

```php
class WeeklyGoalService
{
    // Existing Phase 1 methods (fully implemented and working)
    public function getWeeklyGoalData(User $user): array
    public function calculateWeekProgress(User $user, Carbon $referenceDate): int
    public function getThisWeekReadingDays(User $user): int
    private function getProgressMessage(int $currentProgress, int $weeklyTarget): string
    private function getDefaultWeeklyGoalData(): array
    
    // New Phase 2 methods for weekly streaks
    public function calculateWeeklyStreak(User $user): int
    public function getWeeklyStreakData(User $user): array
    public function isWeekGoalAchieved(User $user, Carbon $weekStart): bool
    private function getStreakMessage(int $streakCount): string
    private function getWeeklyDataWithDateRange(User $user, int $weeksBack): array
}
```

### Dashboard Integration Strategy

**Visual Hierarchy Implementation**:
1. **Current Week Progress** (Primary) - Existing weekly-goal-card with vibrant styling
2. **Weekly Streak** (Secondary) - New weekly-streak-card with complementary but subdued styling
3. **Daily Streak** (Supporting) - Existing streak card maintains current position

**Layout Approach (Option 2 - Summary Stats in Separate Row)**:
- **Top Row**: Weekly Goal | Weekly Streak | Daily Streak (3 cards maximum)
- **Summary Stats Row**: Moves to its own full-width row below (like current LG screen behavior)
- **Benefits**: More breathing room for goal/streak cards, simpler responsive logic, clearer hierarchy
- **Responsive Grid**: 
  - XL screens: `xl:grid-cols-3` for top row (Weekly Goal | Weekly Streak | Daily Streak)
  - LG screens: `lg:grid-cols-3` for top row 
  - Smaller screens: Stack appropriately with existing responsive patterns

**Color Scheme Hierarchy**:
- Weekly Goal (Primary): Green gradient - most prominent
- Weekly Streak (Secondary): Purple/indigo gradient - prominent but secondary  
- Daily Streak (Supporting): Blue gradient - existing styling maintained

### New UI Component Structure

```php
// New component: resources/views/components/ui/weekly-streak-card.blade.php
<x-ui.weekly-streak-card
    :streakCount="$weeklyStreak['streak_count']"
    :isActive="$weeklyStreak['is_active']"
    :motivationalMessage="$weeklyStreak['message']"
    :size="'default'"
    class="h-full" />
```

### Streak Messaging System

**Tiered Messaging Based on Streak Length**:
- **0 weeks**: "Start your first weekly streak!"
- **1 week**: "Great start! Keep the momentum going."
- **2-3 weeks**: "Building consistency! X weeks in a row."
- **4+ weeks**: "Amazing consistency! X weeks strong!"

**Visual Design Consistency**:
- Follows the same card structure as existing weekly-goal-card and streak-counter components
- Uses purple/indigo gradient header to distinguish from green (weekly goal) and blue (daily streak)
- Maintains the same responsive sizing and padding classes
- Includes the same error handling and fallback patterns

## Data Models

### Database Schema (No Changes Required)

Phase 2 continues to use the existing `reading_logs` table without any schema changes:

```sql
-- Optimized weekly streak calculation query
SELECT 
    DATE_TRUNC('week', date_read) as week_start,
    COUNT(DISTINCT DATE(date_read)) as days_read
FROM reading_logs 
WHERE user_id = ? 
  AND date_read < ? -- Before current week start
  AND date_read >= ? -- Start with 52 weeks back, expand if needed
GROUP BY DATE_TRUNC('week', date_read)
ORDER BY week_start DESC
LIMIT 52 -- Initial limit, can be expanded if streak continues
```

### Enhanced Data Structures

**Extended Weekly Goal Data Structure**:
```php
[
    // Existing Phase 1 data (unchanged)
    'current_progress' => 3,
    'weekly_target' => 4,
    'week_start' => '2025-02-09',
    'week_end' => '2025-02-15',
    'is_goal_achieved' => false,
    'progress_percentage' => 75,
    'message' => 'You\'re making progress!',
    
    // New Phase 2 streak data
    'weekly_streak' => [
        'streak_count' => 2,
        'is_active' => true,
        'last_achieved_week' => '2025-02-02',
        'message' => 'Building consistency! 2 weeks in a row.'
    ]
]
```

**Weekly Streak Data Structure** (returned by getWeeklyStreakData):
```php
[
    'streak_count' => 2,               // Number of consecutive weeks
    'is_active' => true,               // Whether streak is currently active
    'last_achieved_week' => '2025-02-02', // Most recent completed week with goal achieved
    'message' => 'Building consistency! 2 weeks in a row.',
]
```

**Integration with UserStatisticsService**:
The weekly streak data will be added to the existing dashboard statistics structure:
```php
// In UserStatisticsService::getDashboardStatistics()
return [
    'streaks' => $this->getStreakStatistics($user),
    'reading_summary' => $this->getReadingSummary($user),
    'book_progress' => $this->getBookProgressSummary($user),
    'recent_activity' => $this->getRecentActivity($user),
    'weekly_goal' => $this->getWeeklyGoalStatistics($user),
    'weekly_streak' => $this->getWeeklyStreakStatistics($user), // New addition
];
```

## Error Handling

### Service Level Error Handling
- **Database Connection Issues**: Return default values (0 streak) with error logging
- **Invalid Date Ranges**: Use safe fallback calculations
- **Streak Calculation Failures**: Return 0 streak with encouraging message
- **User Registration Edge Cases**: Handle users with insufficient history gracefully

### UI Level Error Handling
- **Missing Streak Data**: Display "Start your first weekly streak!" message
- **Service Failures**: Show default state without breaking dashboard layout
- **Partial Data**: Display available information with appropriate messaging

### Graceful Degradation
- If weekly streak calculations fail, weekly goal functionality remains unaffected
- Weekly streak card shows encouraging default state
- Dashboard maintains full functionality even if streak service is unavailable
- Backward compatibility with Phase 1 implementation is preserved

## Testing Strategy - Phase 2

### Unit Tests for Weekly Streak Logic
- **WeeklyGoalService Streak Tests**:
  - Test consecutive week streak calculations
  - Test streak breaking scenarios (week with <4 days)
  - Test edge cases (new users, gaps in data, timezone boundaries)
  - Test streak continuation after achieving weekly goals

### Feature Tests for Dashboard Integration
- **Streak Display Scenarios**:
  - User with active streak sees correct count and messaging
  - User with broken streak sees encouraging restart message
  - User with no history sees appropriate starter message
  - Dashboard displays both weekly goal and streak data correctly

### Test Data Scenarios
```php
// Weekly streak test cases:
- No reading history (0 streak, starter message)
- Single week achieved (1 week streak)
- Multiple consecutive weeks (2-3 week streak)
- Long streak (4+ weeks with celebration)
- Broken streak (previous weeks achieved, current week failed)
- Mixed pattern (some weeks achieved, some not)
```

## Performance Considerations - Phase 2

### Database Optimization for Streak Calculations
- **Efficient Streak Queries**: Iterate backwards through weeks with date range limits
- **Query Optimization**: Use `GROUP BY` with date truncation for weekly aggregation
- **Index Leverage**: Continue using existing indexes on `user_id` and `date_read`
- **Calculation Limits**: Stop streak calculation at reasonable history limit (e.g., 52 weeks max)

### Frontend Performance
- **Component Efficiency**: New streak card follows same lightweight pattern as existing cards
- **HTMX Compatibility**: Streak data included in existing dashboard partial responses
- **Layout Impact**: Minimal CSS additions, reuses existing grid and styling patterns

### Calculation Efficiency
- **Smart Break Detection**: Stop calculating immediately when a week with <4 days is found (don't scan entire history)
- **Date Range Optimization**: Start with reasonable date range (52 weeks), expand if streak is longer than initial range
- **Early Exit Strategy**: Return streak count as soon as break is detected
- **Fallback Strategy**: Return cached/default values if calculations take too long

### Cache Invalidation Strategy
- **Weekly Streak Cache**: Cache invalidated only on Sunday night after week evaluation, not on each reading log
- **Cache Duration**: Weekly streak data cached for 24 hours since streaks only change weekly
- **Cache Key Pattern**: `weekly_streak_{user_id}` for user-specific streak data
- **Performance Benefit**: Eliminates redundant calculations during the week when streak values cannot change

## Implementation Approach - Phase 2

### Backend Extension Strategy
1. Extend existing `WeeklyGoalService` with streak calculation methods
2. Update `UserStatisticsService` to include streak data in dashboard statistics
3. Maintain backward compatibility with Phase 1 implementation

### Frontend Integration Strategy
1. Create new `weekly-streak-card` component following established patterns
2. Update dashboard layout to include streak card with proper visual hierarchy
3. Ensure HTMX partial responses include streak data

### Incremental Development
- Build on solid Phase 1 foundation without breaking existing functionality
- Test each component independently before integration
- Maintain clear separation between weekly goal and weekly streak logic
- Ensure graceful degradation if streak calculations fail

**Focus**: Extend existing system rather than rebuild, maintaining the proven patterns from Phase 1 while adding the achievement layer that users will find motivating.

## Security Considerations

### Data Access Control
- Weekly goal calculations respect user authentication
- Cache keys include user ID to prevent cross-user data access
- No sensitive data exposed in weekly goal statistics

### Input Validation
- Week date calculations use Carbon for safe date handling
- User ID validation through existing authentication middleware
- No user input directly affects weekly goal calculations

**Deferred**: Cache security considerations until caching system is implemented