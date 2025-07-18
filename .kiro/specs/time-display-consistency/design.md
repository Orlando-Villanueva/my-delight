# Design Document

## Overview

This design addresses the time display inconsistency bug where the Recent Readings widget shows incorrect "time ago" values by using `created_at` instead of `date_read`. The solution centralizes time formatting logic and ensures consistent behavior across all components that display reading timestamps.

## Architecture

### Current State Analysis

**Problem Identified:**
- `UserStatisticsService::getRecentActivity()` uses `$reading->created_at` for time calculation
- `ReadingLogController::index()` correctly uses `$log->date_read` for time calculation
- This creates inconsistent displays: same reading shows "2 hours ago" vs "1 day ago"

**Root Cause:**
The bug occurs in line 132 of `UserStatisticsService.php`:
```php
'time_ago' => $this->formatTimeAgo($reading->created_at), // BUG: Should use date_read
```

### Proposed Solution

**Centralized Time Service Approach:**
1. Keep the existing `formatTimeAgo()` method in `UserStatisticsService` as the single source of truth
2. Fix the bug by changing the Recent Readings widget to use `date_read` instead of `created_at`
3. Ensure all time formatting consistently uses `date_read` as the primary timestamp
4. Maintain fallback logic for edge cases where `date_read` might be null

## Components and Interfaces

### UserStatisticsService (Modified)

**Method: `getRecentActivity()`**
- **Change:** Use `$reading->date_read` instead of `$reading->created_at` for time calculation
- **Rationale:** Reading time should reflect when the user actually read the passage, not when they logged it
- **Fallback:** If `date_read` is null, fall back to `created_at`

**Method: `formatTimeAgo()`** 
- **Status:** No changes needed - already works correctly
- **Purpose:** Remains the centralized time formatting logic

### ReadingLogController (Reference Implementation)

**Method: `index()`**
- **Status:** Already correctly implemented
- **Usage:** Serves as the reference for proper time calculation
- **Logic:** Uses `date_read` for `time_ago` and `created_at` for `logged_time_ago`

## Data Models

### ReadingLog Model Fields

**Primary Timestamp Fields:**
- `date_read` (date): When the user actually read the passage - **PRIMARY for time display**
- `created_at` (datetime): When the log entry was created in the system - **SECONDARY/fallback**

**Usage Context:**
- `date_read`: User-facing time displays ("1 day ago", "2 days ago")
- `created_at`: System/audit purposes, fallback when `date_read` is unavailable

## Error Handling

### Null Date Handling

**Scenario:** `date_read` is null or invalid
**Solution:** Fall back to `created_at` with appropriate error logging

```php
$referenceDate = $reading->date_read ?? $reading->created_at;
'time_ago' => $this->formatTimeAgo($referenceDate)
```

### Invalid Date Handling

**Scenario:** Both timestamps are invalid
**Solution:** Return a default message like "unknown time"

## Testing Strategy

### Unit Tests

**Test Cases for `UserStatisticsService::getRecentActivity()`:**
1. **Normal Case:** Reading with valid `date_read` - should use `date_read` for time calculation
2. **Fallback Case:** Reading with null `date_read` - should use `created_at`
3. **Consistency Test:** Same reading data should produce identical time values across components

**Test Cases for `formatTimeAgo()`:**
1. **Time Ranges:** Test various time differences (minutes, hours, days)
2. **Edge Cases:** Test boundary conditions (exactly 1 hour, 24 hours, etc.)
3. **Date Validation:** Test with invalid dates

### Integration Tests

**Cross-Component Consistency:**
1. **Dashboard vs History:** Same reading should show identical "time ago" values
2. **Data Integrity:** Verify `date_read` is properly set during reading log creation
3. **Seeded Data:** Test with existing seeded data to ensure proper handling

### Manual Testing

**User Experience Validation:**
1. **Recent Readings Widget:** Verify shows correct time based on `date_read`
2. **Reading History:** Verify consistency with Recent Readings
3. **Late Logging:** Test readings logged on different day than read

## Implementation Notes

### Minimal Change Approach

**Philosophy:** Fix the bug with minimal code changes to reduce risk
**Strategy:** Single line change in `UserStatisticsService::getRecentActivity()`

### Backward Compatibility

**Existing Data:** All existing reading logs have both `date_read` and `created_at`
**Migration:** No database changes required
**Fallback:** Graceful handling of any edge cases with missing data

### Performance Considerations

**Database Impact:** No additional queries required
**Memory Impact:** No change in data loading patterns
**Caching:** No impact on existing caching strategies