# Implementation Plan - MVP Scope

**Note**: This scope has been reduced to focus on essential functionality for an app with zero users. Complex caching, extensive testing, and advanced features have been deferred until real usage patterns emerge.

## Essential Tasks (Phase 1)

- [x] 1. Create WeeklyGoalService with core calculation methods
  - Create `app/Services/WeeklyGoalService.php` with constructor and basic structure
  - Implement `getCurrentWeekProgress()` method to calculate days read in current week
  - Implement `getWeeklyGoalData()` method to return complete weekly goal data structure
  - Implement helper methods: `getCurrentWeekStart()`, `getDefaultWeeklyGoal()`
  - Add basic error handling and fallback values for all methods
  - _Requirements: 2.1, 2.2, 2.4, 5.1, 5.2_

- [x] 2. Extend UserStatisticsService to include weekly goal data
  - Add weekly goal data to `getDashboardStatistics()` method return array
  - Integrate WeeklyGoalService into UserStatisticsService constructor
  - Add basic unit tests for dashboard statistics integration
  - _Requirements: 1.1, 4.1, 4.4, 5.3_

- [x] 3. Create weekly goal card Blade component
  - Create `resources/views/components/ui/weekly-goal-card.blade.php` component
  - Implement responsive design matching existing dashboard card styling
  - Add progress display in "X/4 days this week" format with appropriate styling
  - Include simple motivational message (basic progress indication)
  - Add simple static text: "Research-backed weekly target"
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 4.3_

- [x] 4. Update DashboardController to pass weekly goal data
  - Modify `index()` method to include weekly goal data in view variables
  - Ensure weekly goal data is passed to both full page and HTMX partial responses
  - Update compact() call to include weekly goal variables
  - Test that weekly goal data is available in dashboard views
  - _Requirements: 4.1, 4.2_

- [x] 5. Integrate weekly goal card into dashboard layout





  - Update `resources/views/partials/dashboard-content.blade.php` to include weekly goal card
  - Position weekly goal card in top stats row alongside streak counter
  - Ensure responsive grid layout accommodates new card (adjust column spans if needed)
  - Test layout on mobile, tablet, and desktop screen sizes
  - Verify HTMX updates include weekly goal card content
  - _Requirements: 1.1, 4.1, 4.2, 4.3, 4.4_