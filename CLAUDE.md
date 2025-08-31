# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Delight** is a Laravel-based Bible reading habit tracker designed to help users establish and maintain consistent Bible reading habits. The core loop is: Read → Log → See Progress → Motivation to Read Again.

**Tech Stack:**
- **Backend:** Laravel 12 with Service Layer Pattern
- **Frontend:** HTMX + Alpine.js with server-driven architecture
- **Database:** SQLite (local development), PostgreSQL (production via Laravel Forge)
- **Authentication:** Laravel Fortify with standard Laravel forms
- **Styling:** Tailwind CSS 4.0
- **Testing:** Pest (PHPUnit)

## Common Development Commands

### Laravel Development
```bash
# Database operations
php artisan migrate
php artisan migrate:fresh --seed
php artisan migrate:status

# Testing
php artisan test
vendor/bin/pest
composer test

# Development utilities  
php artisan tinker
php artisan route:list
php artisan config:show
php artisan db:seed

# Asset building
npm run dev
npm run build
```

### **IMPORTANT: Development Server Constraint**
- **NEVER use `php artisan serve`** - This project uses Laravel Herd for local development
- Laravel Herd provides native web server with .test domains
- Use Laravel Herd's GUI or configured local domains instead
- For testing, use `php artisan test`, route inspection, or Herd's built-in server
- **Note:** `composer dev` script exists but contradicts this policy - use Herd instead

### Code Quality
```bash
# Code formatting (if configured)
./vendor/bin/pint

# Static analysis (if configured)  
./vendor/bin/phpstan analyse
```

## Architecture & Code Organization

### Service Layer Pattern
The application implements a clean Service Layer Pattern for business logic separation:

```
app/
├── Services/
│   ├── ReadingLogService.php         # Core reading log business logic
│   ├── UserStatisticsService.php     # Dashboard statistics & analytics  
│   ├── BookProgressService.php       # Bible book completion tracking
│   ├── BookProgressSyncService.php   # Book progress synchronization
│   ├── BibleReferenceService.php     # Bible reference validation & formatting
│   ├── StreakStateService.php        # Streak calculation logic
│   ├── ReadingFormService.php        # Reading form handling & validation
│   ├── WeeklyGoalService.php         # Weekly reading goal management
│   └── EmailService.php              # Email notifications & communication
├── Http/Controllers/              # Thin controllers delegate to services
├── Models/                        # Eloquent models (User, ReadingLog, BookProgress)
└── View/Components/               # Blade components for UI
```

### Key Services
- **ReadingLogService:** Handles reading log creation, validation, and HTMX responses
- **UserStatisticsService:** Calculates dashboard statistics, streaks, and progress summaries  
- **BibleReferenceService:** Validates Bible references and formats book/chapter data
- **BookProgressService:** Manages denormalized book completion tracking
- **BookProgressSyncService:** Synchronizes book progress across user data
- **ReadingFormService:** Handles reading form processing and validation
- **WeeklyGoalService:** Manages weekly reading goals and progress tracking
- **EmailService:** Handles email notifications and user communications

### HTMX-Native Architecture
- Server returns HTML fragments, not JSON
- Uses `hx-*` attributes for interactions
- Follows Zero-Duplication Component Pattern
- Controllers support both HTMX and standard Laravel request handling

## Database Schema

### Core Tables
```sql
users (id, name, email, password, timestamps)
reading_logs (id, user_id, book_id, chapter, passage_text, date_read, notes_text, timestamps)  
book_progress (id, user_id, book_id, book_name, total_chapters, chapters_read, completion_percent, is_completed, last_updated)
```

### Bible Reference System
- Static configuration in `config/bible.php` (66 books, chapter counts)
- Translations in `lang/en/bible.php` and `lang/fr/bible.php`
- BibleReferenceService handles lookups and validation

## Development Standards

### Code Quality Rules
1. **Always use proper imports** - never fully qualified class names in code
   - ❌ `new \Illuminate\Support\Collection()`
   - ✅ `use Illuminate\Support\Collection;` then `new Collection()`

2. **Never use raw SQL methods** - always use Eloquent and query builder
   - ❌ `selectRaw()`, `whereRaw()`, `havingRaw()`
   - ✅ Eloquent collection methods and Laravel's built-in functions

3. **Alpine.js organization** - extract complex logic from inline x-data
   - ❌ `x-data="{ complex: 'logic here' }"`
   - ✅ `x-data="componentFunction()"` with separate `<script>` tag

### Frontend Standards
- **Tailwind CSS only** - no custom CSS except for framework requirements
- **HTMX-native approach** - use built-in HTMX attributes over JavaScript
- **Progressive enhancement** - features work without JavaScript
- **Mobile-first responsive design**

### Testing Approach
- **Essential verification over comprehensive coverage** for MVP
- Focus on core user workflow: register → log reading → view progress  
- Test streak calculations, Bible reference validation, book progress updates
- Use Pest for unit and feature tests
- **Testing Database:** Uses `database/testing.sqlite` (separate from development database)

## Key Features & Business Logic

### Streak Calculation
- **1-day grace period:** Streak continues if user reads today OR yesterday
- Current streak: consecutive days from present backwards
- Longest streak: maximum consecutive days in user's history
- Timezone-aware date handling

### Bible Reference Handling  
- 66 books with configurable chapter counts
- Whole chapters only (no verse-level tracking in MVP)
- Multi-language support (English/French)
- Structured selectors prevent invalid references

### Book Progress Tracking
- Denormalized `book_progress` table for performance
- Updated incrementally with each reading log
- Tracks chapters read per book as JSON array
- Calculates completion percentages automatically

## Important Project Rules

### Development Environment
- **Use Laravel Herd for local development** - never `php artisan serve`
- SQLite for local development, PostgreSQL for production
- Standard Laravel forms with Fortify authentication (not complex HTMX auth)

### Code Implementation
- Follow Service Layer Pattern - thin controllers, business logic in services
- HTMX returns HTML fragments, controllers handle content negotiation
- Use event-based cache invalidation for user statistics
- Implement proper error handling and validation

### Documentation & Issue Management
- Always check `/docs` directory for established requirements before implementing
- Validate requests against Product Requirements Document
- Never implement features outside documented scope without explicit approval
- Preserve original tasks in Linear issues - don't remove, only mark complete

### Development Guidelines (Post-MVP, ~10 Active Users)
- **Scale Reality Check:** Currently optimizing for 10-100 users, not enterprise scale
- **Time Management:** Balance feature development with user feedback and bug fixes
- **Framework Trust:** Leverage Laravel's built-in solutions for reliability
- **User Impact Focus:** Prioritize features that improve the core reading habit loop
- **Essential Testing:** Focus on functionality that affects real user workflows

### Linear Issue Management
- **Wait for user testing confirmation** before updating Linear issues with completion status
- Allow users to test code changes and verify functionality before marking tasks complete
- Only update Linear issues after receiving explicit user confirmation that changes work correctly

## File Structure Guide

### Views (Zero-Duplication Pattern)
```
resources/views/
├── partials/                    # Shared reusable components
│   ├── dashboard-content.blade.php
│   ├── reading-log-form.blade.php  
│   └── header-update.blade.php
├── components/                  # Blade components
│   ├── ui/ (button, card, input)
│   └── bible/ (book-selector, progress-stats)
└── dashboard.blade.php          # Main views use @include
```

### Key Configuration Files
- `config/bible.php` - Bible book structure and chapter counts
- `lang/*/bible.php` - Localized Bible book names
- `phpunit.xml` - Test configuration with SQLite testing database
- `vite.config.js` - Asset building with Tailwind CSS

## Performance Considerations

### Caching Strategy
- Dashboard statistics cached with 5-minute TTL
- Streak calculations cached with 15-minute TTL  
- Bible reference data cached for 24 hours
- Event-based cache invalidation on new reading logs

### Database Optimization
- Composite indexes on `(user_id, date_read)` for calendar queries
- Denormalized book_progress table reduces complex joins
- Query optimization for streak calculations
- Production PostgreSQL hosted on Laravel Forge

## Common Patterns

### Controller Pattern
```php
public function store(Request $request) 
{
    $result = $this->readingLogService->logReading(
        $request->user(), 
        $request->validated()
    );
    
    if ($request->header('HX-Request')) {
        return view('partials.reading-log-success', compact('result'));
    }
    
    return redirect()->back()->with('success', 'Reading logged!');
}
```

### Service Pattern
```php
class ReadingLogService 
{
    public function __construct(
        private BibleReferenceService $bibleService,
        private BookProgressService $progressService
    ) {}
    
    public function logReading(User $user, array $data): ReadingLog
    {
        // Business logic here
        // Update book progress
        // Clear relevant caches
    }
}
```

This architecture enables rapid development while maintaining clean separation of concerns and high testability.