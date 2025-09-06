# Product Overview

**Delight** is a Bible reading habit tracker designed to help users build and maintain consistent daily Bible reading through simple logging and motivating progress visualization.

## Core Value Proposition
Help users track and build consistency in their Bible reading habits through simple logging and motivating progress visualization.

## Core User Flow
**Read → Log → See Progress (Streak/History) → Motivation to Read Again**

## Key Features (MVP)
- Daily Bible reading log with structured book/chapter selector
- Streak tracking with 1-day grace period
- Visual calendar showing reading history (GitHub-style contribution graph)
- Book completion grid for all 66 Bible books
- Basic statistics (current streak, longest streak, total chapters, books completed)
- Multilingual support (English and French)
- Responsive design for mobile and desktop

## Target Users
- Daily Bible readers seeking consistency
- People wanting to track their Bible reading progress
- Users motivated by streaks and visual progress indicators
- French-speaking users (Quebec market focus)

## Business Model
- Freemium model with core features free
- Pro tier for advanced statistics, enhanced notes, and premium features
- Focus on habit formation and long-term engagement

## Key Business Logic

### Streak Calculation Rules
- **1-day grace period**: Streak continues if user reads today OR yesterday
- **Timezone-aware**: Uses user's local timezone for accurate daily tracking
- **Current streak**: Consecutive days from present backwards
- **Longest streak**: Maximum consecutive days in user's history

### Bible Reference Handling
- **66 books of the Bible** with configurable chapter counts
- **Whole chapters only** (no verse-level tracking in MVP)
- **Structured selectors** prevent invalid references
- **Multi-language support** (English/French book names)

### Progress Tracking
- **Book completion grid** showing all 66 Bible books
- **Color-coded status**: Not started (gray), In progress (blue), Completed (green)
- **Denormalized tracking** for performance via BookProgress table
- **Real-time updates** via HTMX without page reloads

## Development Priorities
- **MVP-focused**: Core features only, no feature creep
- **Performance-first**: Optimized for daily usage patterns
- **Mobile-responsive**: Touch-friendly interface for on-the-go logging
- **Accessibility**: Proper ARIA labels and keyboard navigation