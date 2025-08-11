# Design Document - MVP Scope

## Overview

This design document outlines the **MVP technical implementation** for Phase 1 of the weekly goal system in Delight. The system will track users' weekly Bible reading progress with a default goal of 4 readings per week and display current week progress on the dashboard. The implementation focuses on essential functionality for an app with zero users, deferring complex features until real usage patterns emerge.

**Scope Reduction**: Complex caching, extensive testing, and advanced UI features have been deferred to focus on core functionality that can be enhanced iteratively.

## Architecture

### Service Layer Pattern
The implementation follows the established service layer pattern used throughout the application:
- **WeeklyGoalService**: New service to handle all weekly goal calculations and business logic
- **UserStatisticsService**: Extended to include weekly statistics in dashboard data
- **DashboardController**: Updated to pass weekly goal data to views

### Direct Calculation Strategy (No Caching)
For MVP with zero users, weekly goal statistics will be calculated directly:
- Direct database queries using existing `reading_logs` table
- `COUNT(DISTINCT DATE(date_read))` for efficiency
- No caching complexity - calculations are instant for small datasets
- **Deferred**: Caching system until user load justifies the complexity

### Week Definition
- Week starts on Sunday (as specified in requirements)
- Week identifier format: `YYYY-W##` (e.g., "2025-W06" for the 6th week of 2025)
- Uses Carbon's `startOfWeek(Carbon::SUNDAY)` for consistent week boundaries

## Components and Interfaces

### WeeklyGoalService

```php
class WeeklyGoalService
{
    // Core calculation methods
    public function getCurrentWeekProgress(User $user): array
    public function getWeeklyGoalData(User $user): array
    public function calculateWeekProgress(User $user, Carbon $weekStart): int
    
    // Helper methods
    public function getCurrentWeekStart(): Carbon
    public function getDefaultWeeklyGoal(): int
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

**MVP Approach (Static Text Only)**:
- Subtle gray text under progress: "Research-backed weekly target"
- No JavaScript dependencies
- Always visible but unobtrusive
- **Deferred**: Interactive tooltip system until usage justifies complexity

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
    'progress_percentage' => 75,       // For progress bars (3/4 = 75%)
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

## Testing Strategy - MVP

### Basic Unit Tests
- **WeeklyGoalService Tests**:
  - Test week boundary calculations (Sunday start)
  - Test progress calculations with basic reading patterns
  - Test edge cases (no readings, multiple readings same day)

### Basic Feature Tests
- **Core Scenarios**:
  - User logs reading, weekly progress updates
  - Multiple readings same day count as one day
  - Dashboard displays weekly progress

### Test Data Scenarios
```php
// Essential test cases:
- Empty week (0/4 days)
- Partial progress (2/4 days)
- Goal achieved (4/4 days)
- Multiple readings same day
```

**Deferred**: Comprehensive test suite with 12+ scenarios, cache testing, and integration tests - basic tests sufficient for MVP

## Performance Considerations - MVP

### Database Optimization
- **Efficient Queries**: Use `COUNT(DISTINCT DATE(date_read))` to avoid loading full records
- **Index Usage**: Leverage existing indexes on `user_id` and `date_read`
- **Query Scope**: Limit queries to current week date range only
- **Direct Calculation**: No caching overhead - calculations are instant for small datasets

### Frontend Performance
- **Component Reuse**: Leverage existing card components and styling
- **HTMX Integration**: Minimal JavaScript, server-driven updates
- **Responsive Design**: Uses existing grid system, no additional CSS

**Deferred**: Complex caching strategy until user load justifies the performance optimization

## Implementation Phases - MVP

### Phase 1A: Backend Foundation
1. Create `WeeklyGoalService` with core calculation methods
2. Add weekly goal data to `UserStatisticsService::getDashboardStatistics()`
3. Add basic unit tests for service layer

### Phase 1B: Dashboard Integration
1. Create `weekly-goal-card` Blade component with static research text
2. Update dashboard layout to include weekly goal card
3. Add basic feature tests for user flow

**Total**: 6 essential tasks focusing on core functionality that delivers immediate value to users.

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