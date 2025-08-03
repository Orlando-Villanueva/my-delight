# Implementation Plan

- [x] 1. Create helper method for checking if user has read today





  - Add `hasReadToday()` method to `ReadingFormService` that returns boolean
  - Extract existing logic from `getFormContextData()` method for reusability
  - Write unit tests to verify method returns correct boolean for various scenarios
  - _Requirements: 6.1, 6.3_

- [x] 2. Enhance dashboard data flow to include reading status





  - Modify dashboard controller to call `hasReadToday()` method
  - Pass `hasReadToday` boolean to the streak counter component
  - Update dashboard partial to include new data in component call
  - _Requirements: 6.1, 6.3_

- [x] 3. Implement state detection logic in streak counter component
  - Add `hasReadToday` prop to streak counter component
  - Create private method to determine component state (inactive/active/warning)
  - Implement time-based warning detection (after 6 PM)
  - Write unit tests for state detection logic covering all scenarios
  - _Requirements: 5.1, 5.2, 6.1, 6.2_

- [ ] 4. Create message selection system
  - fetch issue ORL-85 with comments for current implementation context
  - Implement message arrays for different streak ranges and states
  - Create method to select appropriate message based on current streak and state
  - Add logic to rotate between multiple messages for same range
  - Include special messaging for users with previous longest streaks
  - _Requirements: 1.4, 2.3, 3.1, 3.2, 3.3, 3.4, 4.1, 4.2, 6.3, 7.1, 7.2, 7.3_

- [ ] 5. Implement inactive state visual design
  - Change background color to neutral gray when current streak is 0
  - Remove fire icon display when streak is 0
  - Update text to use singular "day" instead of "days" for 0 and 1 day streaks
  - Apply inactive state styling with proper dark mode support
  - _Requirements: 1.1, 1.2, 1.3, 5.1, 5.3_

- [ ] 6. Implement warning state visual design
  - Add orange/amber background gradient for warning state
  - Maintain fire icon but consider color adjustment for warning theme
  - Apply warning state when user hasn't read today and it's past warning time
  - Ensure warning state has proper contrast and accessibility
  - _Requirements: 6.2, 6.4_

- [ ] 7. Fix grammar for singular/plural day text
  - Update component to display "day" (singular) for 1-day streaks
  - Maintain "days" (plural) for streaks greater than 1
  - Apply grammar fix across all states (inactive, active, warning)
  - _Requirements: 1.3, 2.1_

- [ ] 8. Integrate motivational messaging system
  - Replace static "Start your reading journey today!" with dynamic messaging
  - Implement contextual messages based on streak length and state
  - Add urgent messaging for warning state
  - Include acknowledgment messaging when user has read today
  - _Requirements: 1.4, 2.3, 3.1, 3.2, 3.3, 3.4, 4.1, 4.2, 6.3, 7.4_

- [ ] 9. Add comprehensive unit tests for enhanced component
  - Test state detection for all streak values (0, 1, 2-6, 7-13, 14-29, 30+)
  - Test message selection for different ranges and states
  - Test warning state activation based on time and reading status
  - Test proper grammar handling for singular/plural days
  - Test icon visibility logic for different states
  - _Requirements: All requirements validation_

- [ ] 10. Create demo page for streak card states
  - Create temporary route `/streak-demo` for development testing
  - Build demo page showing all possible streak card states in a grid layout
  - Include examples: 0 days (inactive), 1 day, 5 days, 10 days, 20 days, 50+ days
  - Show warning state examples and different message variations
  - Add temporary navigation item for easy access during development
  - _Requirements: Visual validation of all states_

- [ ] 11. Update dashboard integration and test end-to-end functionality
  - Verify streak counter updates correctly when reading logs are added
  - Test HTMX integration maintains proper state updates
  - Verify component responds correctly to cache invalidation
  - Test responsive design across different screen sizes
  - Validate dark mode compatibility for all states
  - _Requirements: All requirements integration testing_