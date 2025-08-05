# Design Document

## Overview

This design enhances the existing streak counter component (`resources/views/components/ui/streak-counter.blade.php`) to provide better visual states and psychologically effective motivational messaging. The enhancement will introduce multiple visual states (inactive, active, warning) and contextual messaging that adapts based on the user's current streak status and reading activity.

## Architecture

### Component Structure

The enhanced streak counter will maintain the same external interface but internally implement a state-based rendering system:

```
StreakCounterComponent
├── State Detection Logic
├── Visual State Rendering
├── Message Selection System
└── Icon Management
```

### State Management

The component will determine its state based on:
- Current streak value (0, 1, 2+)
- Whether user has read today (requires new data)
- Time of day (for warning state)
- Longest streak (for comparative messaging)

## Components and Interfaces

### Enhanced Streak Counter Component

**Location**: `resources/views/components/ui/streak-counter.blade.php`

**New Props**:
```php
@props([
    'currentStreak' => 0,
    'longestStreak' => 0,
    'hasReadToday' => false,  // NEW
    'size' => 'default'
])
```

**State Definitions**:

1. **Inactive State** (currentStreak = 0)
   - Background: Neutral gray (`bg-gray-100 dark:bg-gray-800`)
   - No fire icon
   - Singular "day" text
   - Encouraging start messages

2. **Active State** (currentStreak > 0, hasReadToday = true OR not warning time)
   - Background: Current blue gradient
   - Fire icon present
   - Contextual motivational messages
   - Proper singular/plural grammar

3. **Warning State** (currentStreak > 0, hasReadToday = false, past warning time)
   - Background: Orange/amber gradient (`bg-gradient-to-br from-orange-500 to-orange-600`)
   - Fire icon (possibly orange-tinted)
   - Urgent but encouraging messages

### Message System

**Message Categories**:

```php
// Inactive State (0 days)
$inactiveMessages = [
    'default' => 'Start your reading journey today!',
    'withHistory' => 'You\'ve done it before, you can do it again!',
    'challenge' => 'Ready to beat your record of {longest} days?'
];

// Active State Messages by Range
$activeMessages = [
    1 => ['Great start! Keep it going!', 'You\'re building momentum!'],
    '2-6' => ['You\'re building a great habit!', 'Keep the momentum going!'],
    '7-13' => ['A full week of reading!', 'You\'re on fire!'],
    '14-29' => ['Two weeks strong!', 'You\'re building something amazing!'],
    '30+' => ['A month of dedication!', 'You\'re unstoppable!']
];

// Warning State Messages
$warningMessages = [
    'Don\'t break your streak! Read today!',
    'Your {streak}-day streak needs you!',
    'Keep your momentum going - read today!'
];
```

### Data Requirements

**New Service Method**: `ReadingFormService::hasReadToday(User $user): bool`

This method already exists in `ReadingFormService::getFormContextData()` but needs to be exposed separately:

```php
public function hasReadToday(User $user): bool
{
    return $user->readingLogs()
        ->where('date_read', today()->toDateString())
        ->exists();
}
```

**Dashboard Controller Enhancement**:
The dashboard will need to pass the `hasReadToday` flag to the streak counter component.

## Data Models

### Existing Models (No Changes Required)

- `User` model - unchanged
- `ReadingLog` model - unchanged  
- `UserStatisticsService` - unchanged

### New Data Flow

```
Dashboard Controller
├── Get streak statistics (existing)
├── Check hasReadToday (new)
└── Pass to streak-counter component
```

## Error Handling

### Graceful Degradation

- If `hasReadToday` is not provided, default to `false`
- If message selection fails, fall back to simple default messages
- If time-based logic fails, default to active state

### Edge Cases

1. **Timezone Issues**: Use user's local timezone for "today" calculation
2. **Cache Invalidation**: Ensure `hasReadToday` reflects real-time data
3. **Performance**: Cache message selection to avoid repeated calculations

## Testing Strategy

### Unit Tests

**New Test File**: `tests/Unit/StreakCounterComponentTest.php`

Test cases:
- State detection logic for all streak values
- Message selection for different ranges
- Warning state activation based on time
- Proper singular/plural grammar
- Icon visibility logic

### Feature Tests

**Enhanced Test File**: `tests/Feature/DashboardTest.php`

Test cases:
- Streak counter displays correct state based on user data
- Warning state appears at appropriate times
- Messages update when streak changes
- Component responds to reading log updates

### Visual Regression Tests

- Screenshot tests for each state
- Responsive design verification
- Dark mode compatibility

## Implementation Strategy

### Phase 1: Core State System
1. Enhance `streak-counter.blade.php` with state detection
2. Add `hasReadToday` to dashboard data flow
3. Implement basic inactive/active states

### Phase 2: Message System
1. Create message selection logic
2. Implement contextual messaging
3. Add message rotation system

### Phase 3: Warning State
1. Add time-based warning detection
2. Implement warning visual state
3. Add urgent messaging

### Phase 4: Polish & Testing
1. Comprehensive testing
2. Performance optimization
3. Accessibility improvements

## Performance Considerations

### Caching Strategy

- Cache `hasReadToday` result for 5 minutes
- Cache selected messages to avoid repeated selection
- Invalidate caches when reading logs are updated

### Database Optimization

- Reuse existing `hasReadToday` query from `ReadingFormService`
- No additional database queries required
- Leverage existing streak calculation caching

## Accessibility

### Screen Reader Support

- Proper ARIA labels for different states
- Descriptive text for fire icon presence/absence
- Clear state announcements when streak changes

### Visual Accessibility

- Sufficient color contrast for all states
- Clear visual differentiation between states
- Support for reduced motion preferences

## Browser Compatibility

- CSS gradients with fallbacks
- Responsive design across all breakpoints
- Dark mode support maintained
- HTMX compatibility preserved