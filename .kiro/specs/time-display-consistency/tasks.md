# Implementation Plan
.
- [x] 1. Investigate and fix seeded data timestamp inconsistencies





  - Examine the database seeder to understand how reading logs are created with inconsistent timestamps
  - Fix seeder to create reading logs with `created_at` timestamps that align with `date_read` values
  - Ensure seeded data represents realistic user behavior (readings logged close to when they were read)
  - _Requirements: 1.4, 3.1, 3.2_

- [x] 2. Fix the time calculation bug in UserStatisticsService



  - Change `getRecentActivity()` method to use `date_read` instead of `created_at` for time calculation
  - Add fallback logic to handle cases where `date_read` might be null
  - _Requirements: 1.1, 1.4, 2.2_

- [x] 3. Create unit tests for the time formatting consistency









  - Write test for `getRecentActivity()` method to verify it uses `date_read` for time calculation
  - Write test for fallback behavior when `date_read` is null
  - Write test to verify `formatTimeAgo()` produces consistent results across different contexts
  - _Requirements: 2.1, 2.3, 3.1, 3.2, 3.3, 3.4_

- [ ] 4. Create integration test for cross-component consistency
  - Write test that verifies the same reading log shows identical time values in both Recent Readings widget and reading history
  - Test with both realistic and edge-case timestamp scenarios
  - _Requirements: 1.1, 1.2, 1.3_

- [ ] 5. Add error handling for edge cases
  - Implement proper fallback when both `date_read` and `created_at` are invalid
  - Add appropriate error logging for debugging purposes
  - _Requirements: 2.4_