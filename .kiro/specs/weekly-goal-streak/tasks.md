# Implementation Plan - Phase 2: Weekly Streak System

**Phase 1 Complete**: Current week progress tracking, WeeklyGoalService foundation, and dashboard integration are fully implemented and working.

**Phase 2 Focus**: Add weekly streak calculation and display as a secondary achievement layer that celebrates long-term consistency.

## Phase 2 Tasks

- [ ] 1. Extend WeeklyGoalService with weekly streak calculation methods
  - Add `calculateWeeklyStreak(User $user): int` method to calculate consecutive weeks with achieved goals
  - Add `isWeekGoalAchieved(User $user, Carbon $weekStart): bool` helper method
  - Add `getWeeklyStreakData(User $user): array` method to return complete streak data structure
  - Add `getWeeklyDataWithDateRange(User $user, int $weeksBack): array` helper for optimized queries
  - Implement smart break detection - stop immediately when week with <4 days is found
  - Implement date range optimization - start with 52 weeks, expand if streak continues
  - Add proper error handling and fallback values for streak calculations
  - _Requirements: 2.1, 2.2, 2.3, 4.1, 4.2, 4.4_

- [ ] 2. Create weekly streak card Blade component
  - Create `resources/views/components/ui/weekly-streak-card.blade.php` component
  - Implement secondary styling that complements but doesn't overshadow weekly goal card
  - Add streak count display with appropriate messaging based on streak length
  - Include motivational messages for different streak states (0, 1, 2-3, 4+ weeks)
  - Ensure responsive design matches existing dashboard card patterns
  - _Requirements: 1.1, 1.2, 3.1, 3.3, 3.4, 5.1, 5.2, 5.3, 5.4_

- [ ] 3. Update UserStatisticsService to include weekly streak data
  - Add `getWeeklyStreakStatistics(User $user): array` method to UserStatisticsService
  - Extend `getDashboardStatistics()` method to include `'weekly_streak'` key in return array
  - Add appropriate caching for weekly streak data (similar to existing weekly goal caching)
  - Ensure backward compatibility with existing weekly goal data and caching patterns
  - Add error handling to gracefully handle streak calculation failures
  - _Requirements: 4.1, 4.2, 4.4, 6.3_

- [ ] 4. Integrate weekly streak card into dashboard layout with summary stats reorganization
  - Update `resources/views/partials/dashboard-content.blade.php` to include weekly streak card
  - Position weekly streak card in top row: Weekly Goal | Weekly Streak | Daily Streak
  - Move summary stats component to its own full-width row below the goal/streak cards (like current LG behavior)
  - Update top row grid classes to `xl:grid-cols-3 lg:grid-cols-3` to accommodate 3 cards
  - Update DashboardController to pass weekly streak data to views (extract from stats array)
  - Remove complex responsive hiding/showing of summary stats - always show in separate row
  - Verify HTMX partial responses include weekly streak data and card
  - Test layout on mobile, tablet, and desktop screen sizes
  - _Requirements: 3.1, 3.2, 6.1, 6.2, 6.4, 6.5_

- [ ] 5. Add unit tests for weekly streak calculations
  - Create `tests/Unit/WeeklyGoalServiceTest.php` for testing streak calculation methods
  - Test `calculateWeeklyStreak()` method with various reading patterns (0, 1, 2-3, 4+ weeks)
  - Test streak breaking scenarios (weeks with <4 days reading)
  - Test edge cases: new users, data gaps, timezone boundaries
  - Add feature tests to existing `WeeklyGoalDashboardIntegrationTest.php` for streak display
  - Test that dashboard includes weekly streak data in both regular and HTMX responses
  - _Requirements: 2.1, 2.2, 2.5, 4.4, 5.1, 5.2, 5.3, 5.4, 7.5_