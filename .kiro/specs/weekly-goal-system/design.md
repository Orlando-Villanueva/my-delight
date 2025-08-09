# Design Document

## Overview

This design document outlines the technical implementation for Phase 1 of the weekly goal system in Delight. The system will track users' weekly Bible reading progress with a default goal of 4 readings per week, display current week progress on the dashboard, and provide research-backed contextual messaging. The implementation follows Laravel's service layer pattern and integrates seamlessly with the existing HTMX-driven dashboard architecture.

## Architecture

### Service Layer Pattern
The implementation follows the established service layer pattern used throughout the application:
- **WeeklyGoalService**: New service to handle all weekly goal calculations and business logic
- **UserStatisticsService**: Extended to include weekly statistics in dashboard data
- **ReadingLogService**: Updated to invalidate weekly goal cache when readings are logged
- **DashboardController**: Updated to pass weekly goal data to views

### Caching Strategy
Weekly goal statistics will be cached using Redis (production) or file cache (development) with the following approach:
- Cache key pattern: `user_weekly_goal_{user_id}_{week_identifier}`
- TTL: 300 seconds (5 minutes) for current week data
- Cache invalidation triggers:
  - When a new reading is logged
  - At the start of a new week (handled by TTL and week identifier)
  - Manual cache clearing when needed

### Week Definition
- Week starts on Sunday (as specified in requirements)
- Week identifier format: `YYYY-W##` (e.g., "2025-W06" for the 6th week of 2025)
- Uses Carbon's `startOfWeek(Carbon::SUNDAY)` for consistent week boundaries

## Components and Interfaces

### WeeklyGoalService

```php
class WeeklyGoalService
{
    public function __construct(
        private BibleReferenceService $bibleService
    ) {}

    // Core calculation methods
    public function getCurrentWeekProgress(User $user): array
    public function getWeeklyGoalData(User $user): array
    public function calculateWeekProgress(User $user, Carbon $weekStart): int
    
    // Helper methods
    public function getCurrentWeekStart(): Carbon
    public function getWeekIdentifier(Carbon $date): string
    public function getDefaultWeeklyGoal(): int
    
    // Cache management
    public function invalidateWeeklyGoalCache(User $user): void
    private function getCacheKey(User $user, string $weekIdentifier): string
}
```

### Dashboard Integration

The weekly goal progress will be integrated into the existing dashboard layout:

**Location**: Top stats row, positioned prominently alongside the streak counter
**Display Format**: "3/4 days this week" with appropriate styling and messaging
**Responsive Design**: Adapts to existing grid layout (sm:col-span-2 or similar)

### UI Component Structure

```php
// New Blade component: resources/views/components/ui/weekly-goal-card.blade.php
<x-ui.weekly-goal-card
    :currentProgress="$weeklyGoal['current_progress']"
    :weeklyTarget="$weeklyGoal['weekly_target']"
    :motivationalMessage="$weeklyGoal['message']"
    :showResearchInfo="true"
    class="h-full" />
```

### Research Information Display

Two implementation approaches based on complexity:

**Primary Approach (Tooltip)**:
- Info icon (ℹ️) next to weekly goal header
- Tooltip/popover with research message
- Uses Alpine.js for interactivity

**Fallback Approach (Static Text)**:
- Subtle gray text under progress: "Research-backed weekly target"
- No JavaScript dependencies
- Always visible but unobtrusive

## Data Models

### Database Schema Changes

No new database tables are required. The system will calculate weekly progress from existing `reading_logs` table:

```sql
-- Example query for current week progress
SELECT COUNT(DISTINCT DATE(date_read)) as days_read
FROM reading_logs 
WHERE user_id = ? 
  AND date_read >= ? -- Start of current week (Sunday)
  AND date_read <= ? -- End of current week (Saturday)
```

### Data Structures

**Weekly Goal Data Structure**:
```php
[
    'current_progress' => 3,           // Days read this week
    'weekly_target' => 4,              // Goal (default: 4)
    'week_start' => '2025-02-09',      // Sunday of current week
    'week_end' => '2025-02-15',        // Saturday of current week
    'is_goal_achieved' => false,       // Whether 4+ days reached
    'message' => 'One more to win!',   // Motivational message
    'progress_percentage' => 75,       // For progress bars (3/4 = 75%)
]
```

**Cache Data Structure**:
```php
// Cached under key: user_weekly_goal_{user_id}_{week_identifier}
[
    'days_read' => 3,
    'week_identifier' => '2025-W06',
    'calculated_at' => '2025-02-12 14:30:00',
    'reading_dates' => ['2025-02-09', '2025-02-10', '2025-02-12'], // For debugging
]
```

## Error Handling

### Service Level Error Handling
- **Database Connection Issues**: Return default values (0 progress) with error logging
- **Invalid Date Ranges**: Use current week as fallback
- **Cache Failures**: Calculate values directly without caching
- **User Not Found**: Return empty/default weekly goal data

### UI Level Error Handling
- **Missing Weekly Data**: Display "0/4 days this week" with encouraging message
- **Cache Miss**: Show loading state briefly while calculating
- **JavaScript Errors**: Fallback to static research text instead of tooltip

### Graceful Degradation
- If WeeklyGoalService fails, dashboard continues to function normally
- Weekly goal card shows default state without breaking layout
- Research info falls back to static text if tooltip fails

## Testing Strategy

### Unit Tests
- **WeeklyGoalService Tests**:
  - Test week boundary calculations (Sunday start)
  - Test progress calculations with various reading patterns
  - Test cache key generation and invalidation
  - Test motivational message selection logic
  - Test edge cases (no readings, goal exceeded, etc.)

### Integration Tests
- **Dashboard Integration**:
  - Test weekly goal data appears in dashboard statistics
  - Test cache invalidation when readings are logged
  - Test HTMX updates include weekly goal data

### Feature Tests
- **End-to-End Scenarios**:
  - User logs reading, weekly progress updates
  - Week transitions reset progress correctly
  - Multiple readings same day count as one day
  - Dashboard displays correct motivational messages

### Test Data Scenarios
```php
// Test cases to cover:
- Empty week (0/4 days)
- Partial progress (1/4, 2/4, 3/4 days)
- Goal achieved (4/4 days)
- Goal exceeded (5+/4 days)
- Week boundary transitions
- Multiple readings same day
- Readings on different days
- Cache hit/miss scenarios
```

## Performance Considerations

### Database Optimization
- **Efficient Queries**: Use `COUNT(DISTINCT DATE(date_read))` to avoid loading full records
- **Index Usage**: Leverage existing indexes on `user_id` and `date_read`
- **Query Scope**: Limit queries to current week date range only

### Caching Strategy
- **Cache Duration**: 5-minute TTL balances freshness with performance
- **Cache Keys**: Include week identifier to auto-invalidate on week transitions
- **Cache Size**: Minimal data cached (just counts and dates)
- **Cache Warming**: No pre-warming needed, calculated on-demand

### Frontend Performance
- **Component Reuse**: Leverage existing card components and styling
- **HTMX Integration**: Minimal JavaScript, server-driven updates
- **Responsive Design**: Uses existing grid system, no additional CSS

## Implementation Phases

### Phase 1A: Backend Foundation
1. Create `WeeklyGoalService` with core calculation methods
2. Add weekly goal data to `UserStatisticsService::getDashboardStatistics()`
3. Update `ReadingLogService` to invalidate weekly goal cache
4. Add unit tests for service layer

### Phase 1B: Dashboard Integration
1. Create `weekly-goal-card` Blade component
2. Update dashboard layout to include weekly goal card
3. Implement motivational messaging logic
4. Add integration tests

### Phase 1C: Research Information
1. Implement info icon with tooltip (primary approach)
2. Add fallback static text option
3. Test both approaches across devices
4. Add feature tests for complete user flow

## Security Considerations

### Data Access Control
- Weekly goal calculations respect user authentication
- Cache keys include user ID to prevent cross-user data access
- No sensitive data exposed in weekly goal statistics

### Input Validation
- Week date calculations use Carbon for safe date handling
- User ID validation through existing authentication middleware
- No user input directly affects weekly goal calculations

### Cache Security
- Cache keys are predictable but user-scoped
- No sensitive information stored in cache (just counts)
- Cache invalidation prevents stale data issues