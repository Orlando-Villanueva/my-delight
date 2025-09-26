# Milestone System Specification

## Overview

The Milestone System detects and celebrates user achievements when logging Bible reading. Milestones are displayed in the success modal after a reading log is submitted, providing positive reinforcement for consistent reading habits.

---

## Core Principles

1. **Unique Chapter Counting** - All chapter-based milestones count UNIQUE chapters read, not re-readings
2. **Universal Book Celebrations** - Every Bible book (all 66) gets a completion celebration
3. **Progressive Encouragement** - Milestones increase in intervals to maintain motivation
4. **Context-Aware Display** - Only show milestones achieved by the current reading log

---

## Milestone Categories

### 1. Book Completion Milestones

**Trigger:** User completes all chapters of a Bible book for the first time

**All 66 Books Supported:**

**Old Testament (39 books):**
- Genesis, Exodus, Leviticus, Numbers, Deuteronomy
- Joshua, Judges, Ruth, 1 Samuel, 2 Samuel, 1 Kings, 2 Kings
- 1 Chronicles, 2 Chronicles, Ezra, Nehemiah, Esther
- Job, Psalms, Proverbs, Ecclesiastes, Song of Solomon
- Isaiah, Jeremiah, Lamentations, Ezekiel, Daniel
- Hosea, Joel, Amos, Obadiah, Jonah, Micah, Nahum
- Habakkuk, Zephaniah, Haggai, Zechariah, Malachi

**New Testament (27 books):**
- Matthew, Mark, Luke, John, Acts
- Romans, 1 Corinthians, 2 Corinthians, Galatians, Ephesians
- Philippians, Colossians, 1 Thessalonians, 2 Thessalonians
- 1 Timothy, 2 Timothy, Titus, Philemon
- Hebrews, James, 1 Peter, 2 Peter
- 1 John, 2 John, 3 John, Jude, Revelation

**Message Format:**
```
🎉 You finished {BookName}!
```

**Examples:**
- `🎉 You finished Genesis!`
- `🎉 You finished Psalms!`
- `🎉 You finished John!`
- `🎉 You finished Revelation!`

---

### 2. Unique Chapter Milestones

**Trigger:** User reaches specific unique chapter count thresholds

**Milestone Thresholds:**
```php
10, 25, 50, 75, 100, 150, 200, 250, 300, 400, 500, 600, 700, 800, 900, 1000, 1189
```

**Message Mapping:**
```php
10   => '📖 10 Unique Chapters Read!'
25   => '📖 25 Unique Chapters Read!'
50   => '📚 50 Unique Chapters Read!'
75   => '📚 75 Unique Chapters Read!'
100  => '💯 100 Unique Chapters Read!'
150  => '🎯 150 Unique Chapters Read!'
200  => '🎯 200 Unique Chapters Read!'
250  => '🔥 250 Unique Chapters Read!'
300  => '🔥 300 Unique Chapters Read!'
400  => '⭐ 400 Unique Chapters Read!'
500  => '🏆 500 Unique Chapters Read!'
600  => '🏆 600 Unique Chapters Read!'
700  => '👑 700 Unique Chapters Read!'
800  => '👑 800 Unique Chapters Read!'
900  => '🌟 900 Unique Chapters Read!'
1000 => '🌟 1000 Unique Chapters Read!'
1189 => '⭐ Full Bible Complete - All 1189 Chapters!'
```

**Important:** The dashboard "Chapters Read" stat counts ALL reading logs (including re-readings). Milestone chapter counts only count UNIQUE chapters.

---

### 3. Testament Completion Milestones

**Old Testament Completion:**
- **Trigger:** All 929 unique Old Testament chapters read
- **Message:** `📜 Old Testament Complete!`

**New Testament Completion:**
- **Trigger:** All 260 unique New Testament chapters read
- **Message:** `✝️ New Testament Complete!`

**Whole Bible Completion:**
- **Trigger:** All 1189 unique chapters read (both testaments)
- **Message:** `🏆 Complete Bible Achievement Unlocked!`
- **Note:** This is distinct from the 1189 chapter milestone and shows when both testaments are 100% complete

---

## Service Architecture

### MilestoneService

**Location:** `app/Services/MilestoneService.php`

**Dependencies:**
- `BibleReferenceService` - Bible structure and chapter counts
- `BookProgressService` - Book completion status
- `UserStatisticsService` - Total unique chapters read

**Core Methods:**

#### `detectMilestones(ReadingLog $log, User $user): array`

Detects all milestones achieved by the current reading log.

**Returns:**
```php
[
    [
        'type' => 'book_completion',
        'icon' => '🎉',
        'message' => 'You finished Genesis!',
        'book_name' => 'Genesis',
    ],
    [
        'type' => 'chapter_milestone',
        'icon' => '💯',
        'message' => '100 Unique Chapters Read!',
        'chapter_count' => 100,
    ],
    [
        'type' => 'testament_completion',
        'icon' => '✝️',
        'message' => 'New Testament Complete!',
        'testament' => 'New',
    ],
]
```

**Logic Flow:**
1. Get user's previous unique chapter count (BEFORE current log)
2. Calculate new unique chapters added by current log
3. Check if any milestones crossed with this log
4. Return array of detected milestones

---

### Integration with ReadingLogService

**Method:** `getSuccessModalData(ReadingLog $log, User $user): array`

**Updated Return Structure:**
```php
[
    'showStreak' => $isFirstLogToday,
    'currentStreak' => $isFirstLogToday ? $streakData['current_streak'] : null,

    'showBookProgress' => $hasNewProgress,
    'bookProgress' => $hasNewProgress ? [...] : null,

    'milestones' => $this->milestoneService->detectMilestones($log, $user),
    'hasMilestones' => count($milestones) > 0,
]
```

---

## Implementation Details

### Unique Chapter Tracking

**Data Source:** `book_progress.chapters_read` JSON arrays

**Calculation Method:**
```php
// Get all book_progress records for user
$allProgress = $user->bookProgress()->get();

// Flatten all chapters_read arrays
$uniqueChapters = collect();
foreach ($allProgress as $progress) {
    $chapters = $progress->chapters_read ?? [];
    foreach ($chapters as $chapter) {
        $uniqueChapters->push([
            'book_id' => $progress->book_id,
            'chapter' => $chapter,
        ]);
    }
}

// Count unique book+chapter combinations
$totalUniqueChapters = $uniqueChapters->unique(function($item) {
    return $item['book_id'] . '_' . $item['chapter'];
})->count();
```

### Testament Completion Detection

**Old Testament:**
```php
// Get all OT book progress records
$otBooks = $bibleReferenceService->listBibleBooks()
    ->where('testament', 'old');

// Count unique OT chapters read
$otChaptersRead = $this->countTestamentUniqueChapters($user, 'old');

// OT has 929 total chapters
$isOtComplete = $otChaptersRead === 929;
```

**New Testament:**
```php
// NT has 260 total chapters
$ntChaptersRead = $this->countTestamentUniqueChapters($user, 'new');
$isNtComplete = $ntChaptersRead === 260;
```

---

## Milestone Detection Logic

### Before/After Comparison

**Critical Requirement:** Only show milestones achieved by THIS reading log, not all milestones ever achieved.

**Algorithm:**
```php
public function detectMilestones(ReadingLog $log, User $user): array
{
    $milestones = [];

    // 1. Get state BEFORE this log
    $previousUniqueChapters = $this->getPreviousUniqueChapterCount($user, $log);

    // 2. Get state AFTER this log (current state)
    $currentUniqueChapters = $this->getCurrentUniqueChapterCount($user);

    // 3. Check chapter milestones
    $chapterMilestone = $this->checkChapterMilestone(
        $previousUniqueChapters,
        $currentUniqueChapters
    );
    if ($chapterMilestone) {
        $milestones[] = $chapterMilestone;
    }

    // 4. Check book completion
    $bookMilestone = $this->checkBookCompletion($log, $user);
    if ($bookMilestone) {
        $milestones[] = $bookMilestone;
    }

    // 5. Check testament completion
    $testamentMilestone = $this->checkTestamentCompletion(
        $previousUniqueChapters,
        $currentUniqueChapters,
        $user
    );
    if ($testamentMilestone) {
        $milestones[] = $testamentMilestone;
    }

    return $milestones;
}
```

### Chapter Milestone Crossing Detection

```php
private function checkChapterMilestone(int $before, int $after): ?array
{
    $thresholds = [10, 25, 50, 75, 100, 150, 200, 250, 300,
                   400, 500, 600, 700, 800, 900, 1000, 1189];

    // Find the highest threshold crossed
    foreach (array_reverse($thresholds) as $threshold) {
        if ($before < $threshold && $after >= $threshold) {
            return [
                'type' => 'chapter_milestone',
                'icon' => $this->getChapterIcon($threshold),
                'message' => $this->getChapterMessage($threshold),
                'chapter_count' => $threshold,
            ];
        }
    }

    return null;
}
```

**Note:** If a user logs 30 chapters at once and crosses multiple thresholds (e.g., from 0 to 30), only show the HIGHEST milestone achieved (e.g., "25 Unique Chapters Read!").

---

## UI Display

### Success Modal Integration

**Display Order:**
1. Streak celebration (if first log of day)
2. **Milestones** (new addition)
3. Book progress (if new chapters logged)

**Blade Template:**
```blade
{{-- Milestones Section --}}
@if($hasMilestones)
    <div class="space-y-3">
        @foreach($milestones as $milestone)
            <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-accent-50 to-orange-50 dark:from-accent-900/20 dark:to-orange-900/20 rounded-xl border-2 border-accent-200 dark:border-accent-700">
                <div class="text-4xl">{{ $milestone['icon'] }}</div>
                <div class="flex-1">
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $milestone['message'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endif
```

**Confetti Trigger:** Fire confetti animation for ANY milestone detection (not just streak milestones).

---

## Configuration File

**Location:** `config/milestones.php`

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chapter Milestone Thresholds
    |--------------------------------------------------------------------------
    |
    | Define unique chapter count thresholds that trigger milestone celebrations.
    | These counts refer to UNIQUE chapters read, not total reading logs.
    |
    */

    'chapter_thresholds' => [
        10 => ['icon' => '📖', 'message' => '10 Unique Chapters Read!'],
        25 => ['icon' => '📖', 'message' => '25 Unique Chapters Read!'],
        50 => ['icon' => '📚', 'message' => '50 Unique Chapters Read!'],
        75 => ['icon' => '📚', 'message' => '75 Unique Chapters Read!'],
        100 => ['icon' => '💯', 'message' => '100 Unique Chapters Read!'],
        150 => ['icon' => '🎯', 'message' => '150 Unique Chapters Read!'],
        200 => ['icon' => '🎯', 'message' => '200 Unique Chapters Read!'],
        250 => ['icon' => '🔥', 'message' => '250 Unique Chapters Read!'],
        300 => ['icon' => '🔥', 'message' => '300 Unique Chapters Read!'],
        400 => ['icon' => '⭐', 'message' => '400 Unique Chapters Read!'],
        500 => ['icon' => '🏆', 'message' => '500 Unique Chapters Read!'],
        600 => ['icon' => '🏆', 'message' => '600 Unique Chapters Read!'],
        700 => ['icon' => '👑', 'message' => '700 Unique Chapters Read!'],
        800 => ['icon' => '👑', 'message' => '800 Unique Chapters Read!'],
        900 => ['icon' => '🌟', 'message' => '900 Unique Chapters Read!'],
        1000 => ['icon' => '🌟', 'message' => '1000 Unique Chapters Read!'],
        1189 => ['icon' => '⭐', 'message' => 'Full Bible Complete - All 1189 Chapters!'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Book Completion Messages
    |--------------------------------------------------------------------------
    |
    | Template for book completion milestones.
    | Every Bible book uses the same celebration format.
    |
    */

    'book_completion' => [
        'icon' => '🎉',
        'message_template' => 'You finished {book_name}!',
    ],

    /*
    |--------------------------------------------------------------------------
    | Testament Completion Messages
    |--------------------------------------------------------------------------
    |
    | Celebrate when user completes all chapters of a testament.
    |
    | Old Testament: 929 chapters
    | New Testament: 260 chapters
    |
    */

    'testament_completion' => [
        'old' => [
            'icon' => '📜',
            'message' => 'Old Testament Complete!',
            'total_chapters' => 929,
        ],
        'new' => [
            'icon' => '✝️',
            'message' => 'New Testament Complete!',
            'total_chapters' => 260,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Full Bible Completion
    |--------------------------------------------------------------------------
    |
    | Ultimate achievement: all 1189 unique chapters read.
    |
    */

    'bible_completion' => [
        'icon' => '🏆',
        'message' => 'Complete Bible Achievement Unlocked!',
        'total_chapters' => 1189,
    ],

];
```

---

## Testing Requirements

### Unit Tests - `MilestoneServiceTest.php`

**Test Cases:**

1. ✅ **Detects book completion milestone**
   - User completes Genesis (read all 50 chapters)
   - Assert milestone returned with correct book name

2. ✅ **Detects chapter milestone crossing**
   - User has 95 unique chapters, logs 10 new chapters (total 105)
   - Assert 100-chapter milestone detected

3. ✅ **Only detects highest milestone when multiple crossed**
   - User has 0 unique chapters, logs 30 chapters
   - Assert only 25-chapter milestone detected (not 10)

4. ✅ **Does not detect milestone if not crossed**
   - User has 50 unique chapters, logs re-reading
   - Assert no milestones detected

5. ✅ **Detects testament completion**
   - User completes last OT book (all 929 chapters)
   - Assert OT completion milestone detected

6. ✅ **Detects full Bible completion**
   - User reads final chapter to reach 1189 unique chapters
   - Assert full Bible milestone detected

7. ✅ **Handles re-readings correctly**
   - User re-reads Genesis chapter 1 (already read before)
   - Assert no new unique chapters, no milestones detected

---

## Performance Considerations

### Caching Strategy

**Unique Chapter Count Cache:**
```php
Cache::remember(
    "user_unique_chapter_count_{$user->id}",
    3600, // 1 hour TTL
    fn() => $this->calculateTotalUniqueChapters($user)
);
```

**Cache Invalidation:**
- Invalidate when new reading log created
- Handled by existing `UserStatisticsService::invalidateUserCache()`

### Query Optimization

**Avoid N+1:**
```php
// ✅ Good: Single query with eager loading
$allProgress = $user->bookProgress()->get();

// ❌ Bad: N queries for each book
foreach ($books as $book) {
    $progress = $user->bookProgress()->where('book_id', $book->id)->first();
}
```

---

## Edge Cases

### Multi-Chapter Range Logs

**Scenario:** User logs "Genesis 1-10" in a single submission

**Behavior:**
- If user has 0 chapters, goes to 10 chapters → Show "10 Unique Chapters Read!"
- If user has 95 chapters, goes to 105 chapters → Show "100 Unique Chapters Read!"

### Same-Day Multiple Logs

**Scenario:** User logs Matthew 1-5, then Matthew 6-10 on same day

**Behavior:**
- First log: Potential milestones detected
- Second log: New milestones detected independently
- Each success modal shows its own milestones

### Book Completion with Testament Completion

**Scenario:** User completes Malachi (last OT book), achieving both book completion AND OT completion

**Behavior:**
- Show BOTH milestones in success modal:
  1. `🎉 You finished Malachi!`
  2. `📜 Old Testament Complete!`

### Re-reading Entire Books

**Scenario:** User re-reads Genesis after already completing it

**Behavior:**
- No book completion milestone (already achieved)
- No chapter milestones (no new unique chapters)
- Book progress card still shows completion status

---

## Future Enhancements (Post-MVP)

- **Milestone History Page:** View all achieved milestones with dates
- **Share Milestones:** Social sharing for major achievements
- **Milestone Badges:** Visual badges on profile/dashboard
- **Custom Milestones:** User-defined reading goals
- **Yearly Milestones:** "Read 365 chapters this year"
- **Speed Milestones:** "Completed Genesis in 7 days"

---

## Summary

The Milestone System provides contextual celebration for user achievements during Bible reading. By detecting book completions, unique chapter milestones, and testament completions, it reinforces positive reading habits and provides clear progress markers toward larger goals.

**Key Differentiators:**
- **Unique chapter counting** prevents re-reading inflation
- **Universal book celebrations** treat all 66 books equally
- **Before/after comparison** only shows NEW achievements
- **Confetti integration** provides visual celebration

This system complements the existing streak messaging and book progress tracking to create a comprehensive motivation framework.
