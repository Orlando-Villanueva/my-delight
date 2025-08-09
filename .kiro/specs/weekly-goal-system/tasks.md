# Implementation Plan

- [ ] 1. Create WeeklyGoalService with core calculation methods
  - Create `app/Services/WeeklyGoalService.php` with constructor and basic structure
  - Implement `getCurrentWeekProgress()` method to calculate days read in current week
  - Implement `getWeeklyGoalData()` method to return complete weekly goal data structure
  - Implement helper methods: `getCurrentWeekStart()`, `getWeekIdentifier()`, `getDefaultWeeklyGoal()`
  - Add proper error handling and fallback values for all methods
  - _Requirements: 2.1, 2.2, 2.4, 5.1, 5.2_

- [ ] 2. Implement weekly progress calculation logic
  - Add `calculateWeekProgress()` method that queries reading logs for specific week
  - Use `COUNT(DISTINCT DATE(date_read))` query pattern for efficiency
  - Implement Sunday-based week boundary calculations using Carbon
  - Handle timezone considerations and edge cases (no readings, invalid dates)
  - Add comprehensive unit tests covering all calculation scenarios
  - _Requirements: 2.1, 2.2, 2.4, 3.1, 3.3, 5.5_

- [ ] 3. Implement caching system for weekly goal statistics
  - Add cache key generation method with pattern `user_weekly_goal_{user_id}_{week_identifier}`
  - Implement caching in `getWeeklyGoalData()` with 5-minute TTL
  - Add `invalidateWeeklyGoalCache()` method for cache management
  - Handle cache failures gracefully with direct calculation fallback
  - Test cache hit/miss scenarios and week transition behavior
  - _Requirements: 3.5, 3.6, 3.7, 5.4_

- [ ] 4. Update ReadingLogService to invalidate weekly goal cache
  - Modify `invalidateUserStatisticsCache()` method to include weekly goal cache keys
  - Update `logReading()` method to trigger weekly goal cache invalidation
  - Ensure cache invalidation works for both single and multiple chapter logging
  - Test cache invalidation triggers when new readings are added
  - _Requirements: 3.6, 5.3_

- [ ] 5. Extend UserStatisticsService to include weekly goal data
  - Add weekly goal data to `getDashboardStatistics()` method return array
  - Integrate WeeklyGoalService into UserStatisticsService constructor
  - Ensure weekly goal data is included in cached dashboard statistics
  - Update existing cache invalidation to handle weekly goal data
  - Add unit tests for dashboard statistics integration
  - _Requirements: 1.1, 4.1, 4.4, 5.3_

- [ ] 6. Implement motivational messaging system
  - Create message selection logic based on current progress (0/4, 1/4, 2/4, 3/4, 4+/4)
  - Implement encouraging messages for each progress state
  - Add celebratory messaging for goal achievement with appropriate tone
  - Create helper method to determine message based on progress and goal status
  - Test all message scenarios and edge cases
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [ ] 7. Create weekly goal card Blade component
  - Create `resources/views/components/ui/weekly-goal-card.blade.php` component
  - Implement responsive design matching existing dashboard card styling
  - Add progress display in "X/4 days this week" format with appropriate styling
  - Include motivational message display with proper typography
  - Add conditional styling based on progress state (0, partial, achieved)
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 4.3_

- [ ] 8. Implement research-backed contextual help feature
  - Add info icon (ℹ️) next to weekly goal header in card component
  - Implement tooltip functionality using Alpine.js with research message
  - Add fallback static text option: "Research-backed weekly target"
  - Test tooltip behavior across different devices and screen sizes
  - Ensure contextual help is unobtrusive and optional for users
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 9. Update DashboardController to pass weekly goal data
  - Modify `index()` method to include weekly goal data in view variables
  - Ensure weekly goal data is passed to both full page and HTMX partial responses
  - Update compact() call to include weekly goal variables
  - Test that weekly goal data is available in dashboard views
  - _Requirements: 4.1, 4.2_

- [ ] 10. Integrate weekly goal card into dashboard layout
  - Update `resources/views/partials/dashboard-content.blade.php` to include weekly goal card
  - Position weekly goal card in top stats row alongside streak counter
  - Ensure responsive grid layout accommodates new card (adjust column spans if needed)
  - Test layout on mobile, tablet, and desktop screen sizes
  - Verify HTMX updates include weekly goal card content
  - _Requirements: 1.1, 4.1, 4.2, 4.3, 4.4_

- [ ] 11. Add comprehensive unit tests for WeeklyGoalService
  - Test `getCurrentWeekProgress()` with various reading patterns
  - Test week boundary calculations and Sunday start behavior
  - Test cache key generation and week identifier format
  - Test motivational message selection for all progress states
  - Test error handling and fallback scenarios
  - _Requirements: 5.5_

- [ ] 12. Add integration tests for dashboard weekly goal display
  - Test weekly goal data appears correctly in dashboard statistics
  - Test cache invalidation when readings are logged via ReadingLogService
  - Test HTMX dashboard updates include updated weekly goal progress
  - Test week transition scenarios and progress reset behavior
  - Test multiple readings same day count as single day toward goal
  - _Requirements: 1.5, 2.3, 2.5_