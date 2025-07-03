# Bible Reading Habit Builder - Technical Architecture

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                          Client Layer                               │
│                                                                     │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │  Web Browser  │    │   iOS App     │    │  Android App  │        │
│  │  (HTMX +      │    │  (Swift)      │    │  (Kotlin)     │        │
│  │   Alpine.js)  │    │               │    │               │        │
│  └───────┬───────┘    └───────┬───────┘    └───────┬───────┘        │
│          │                    │                    │                │
└──────────┼────────────────────┼────────────────────┼────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        Communication Layer                          │
│                                                                     │
│  ┌───────────────────────┐      ┌───────────────────────────┐       │
│  │      Web Routes       │      │      API Routes           │       │
│  │  (HTML/HTMX Responses)│      │   (JSON Responses)        │       │
│  └───────────┬───────────┘      └──────────────┬────────────┘       │
│              │                                 │                    │
└──────────────┼─────────────────────────────────┼────────────────────┘
               │                                 │
               ▼                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                       Application Layer                             │
│                                                                     │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │  Controllers  │    │   Services    │    │  Middleware   │        │
│  │               │    │               │    │               │        │
│  └───────┬───────┘    └───────┬───────┘    └───────┬───────┘        │
│          │                    │                    │                │
└──────────┼────────────────────┼────────────────────┼────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Service Layer                                   │
│                                                                     │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │   Services    │    │  Repositories │    │   Models      │        │
│  │               │    │               │    │               │        │
│  └───────┬───────┘    └───────┬───────┘    └───────┬───────┘        │
│          │                    │                    │                │
└──────────┼────────────────────┼────────────────────┼────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         Domain Layer                                │
│                                                                     │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │    Models     │    │  Repositories │    │ Domain Logic  │        │
│  │               │    │               │    │               │        │
│  └───────┬───────┘    └───────┬───────┘    └───────────────┘        │
│          │                    │                                     │
└──────────┼────────────────────┼─────────────────────────────────────┘
           │                    │
           ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Infrastructure Layer                           │
│                                                                     │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │   Database    │    │ External APIs │    │   Caching     │        │
│  │  (PostgreSQL) │    │ (Bible APIs)  │    │   (Redis)     │        │
│  └───────────────┘    └───────────────┘    └───────────────┘        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

For detailed component relationships, data flow diagrams, and integration patterns, see:
- [Application Architecture Diagram](./Application%20Architecture%20Diagram.md)

## Service Layer Architecture Implementation

This project implements the **Service Layer Pattern**, which provides clear separation of concerns, highly testable code organization, and follows modern Laravel best practices. The Service Layer acts as an intermediary between controllers and models, encapsulating business logic and promoting code reusability.

### Service Layer Benefits

- **Separation of Concerns**: Business logic is separated from presentation and data access layers
- **Reusability**: Services can be reused across different controllers, commands, and jobs
- **Testability**: Services can be easily unit tested in isolation
- **Maintainability**: Clean, organized code structure that's easy to modify and extend
- **Thin Controllers**: Controllers remain focused on HTTP concerns only

### Service Layer Structure

```
app/
├── Services/
│   ├── ReadingLogService.php      # Reading log business logic
│   ├── UserStatisticsService.php  # Dashboard and statistics
│   └── BookProgressService.php    # Book progress tracking
├── Models/                        # Eloquent models
├── Http/
│   ├── Controllers/              # Thin controllers
│   └── Requests/                 # Form validation
└── Repositories/                 # Data access (optional)
```

### Service Layer Implementation

#### ReadingLogService ✅ **IMPLEMENTED**
Handles Bible reading log business logic with HTMX integration:

**Core Features:**
- ✅ Bible reference validation via BibleReferenceService
- ✅ Auto-formatting ("Genesis 1", "John 1-3") 
- ✅ Multi-chapter support (ranges as separate entries)
- ✅ Book progress tracking with JSON chapters
- ✅ Streak calculations (current + longest)
- ✅ Reading history with filtering
- ✅ HTMX-native responses (no complex JavaScript)
```

#### UserStatisticsService
Handles dashboard statistics and user progress calculations:

```php
namespace App\Services;

class UserStatisticsService
{
    public function __construct(
        private ReadingLogService $readingLogService
    ) {}

    /**
     * Get comprehensive dashboard statistics for a user.
     */
    public function getDashboardStatistics(User $user): array
    {
        return [
            'streaks' => $this->getStreakStatistics($user),
            'reading_summary' => $this->getReadingSummary($user),
            'book_progress' => $this->getBookProgressSummary($user),
            'recent_activity' => $this->getRecentActivity($user),
        ];
    }

    /**
     * Get calendar data for visualization (GitHub-style contribution graph).
     */
    public function getCalendarData(User $user, ?string $year = null): array
    {
        // Calendar visualization logic
    }
}
```

### Controller Integration

Controllers remain thin and delegate business logic to services:

```php
namespace App\Http\Controllers;

use App\Services\ReadingLogService;
use App\Services\UserStatisticsService;

class ReadingLogController extends Controller
{
    public function __construct(
        private ReadingLogService $readingLogService,
        private UserStatisticsService $statisticsService
    ) {}

    public function store(StoreReadingLogRequest $request)
    {
        $readingLog = $this->readingLogService->logReading(
            $request->user(),
            $request->validated()
        );

        if ($request->expectsJson()) {
            return response()->json($readingLog);
        }

        return redirect()->back()->with('success', 'Reading logged successfully!');
    }

    public function dashboard(Request $request)
    {
        $statistics = $this->statisticsService->getDashboardStatistics($request->user());
        $calendarData = $this->statisticsService->getCalendarData($request->user());

        return view('dashboard', compact('statistics', 'calendarData'));
    }
}
```

## Data Models (Entity Relationship Diagram)

```
┌───────────────────┐       ┌───────────────────┐       ┌───────────────────┐
│       Users       │       │    ReadingLogs    │       │   BookProgress    │
├───────────────────┤       ├───────────────────┤       ├───────────────────┤
│ id (PK)           │       │ id (PK)           │       │ id (PK)           │
│ name              │◄──────┤ user_id (FK)      │       │ user_id (FK)      │
│ email             │       │ book_id           │       │ book_id           │
│ password          │       │ chapter           │       │ book_name         │
│ email_verified_at │       │ passage_text      │       │ total_chapters    │
│ remember_token    │       │ date_read         │       │ chapters_read     │
│ created_at        │       │ notes_text        │       │ completion_percent│
│ updated_at        │       │ created_at        │       │ is_completed      │
└───────────────────┘       │ updated_at        │       │ last_updated      │
                            └───────────────────┘       └───────────────────┘
```

**Note:** The `BookProgress` table is a denormalized structure that tracks each user's reading progress for each book of the Bible. There is no direct database relationship (such as a foreign key) between `ReadingLogs` and `BookProgress`. Instead, `BookProgress` is updated whenever a new `ReadingLog` is created or modified. This serves as a performance optimization for statistics calculations, eliminating the need to scan all reading logs when checking book completion status.

**MVP-Focused Data Model**: The initial implementation will focus only on the core entities (Users, ReadingLogs, and BookProgress) needed for the MVP's "Read → Log → See Progress" flow. Additional entities like Goals, Achievements, Tags, and ReadingPlans will be implemented in later phases as the application evolves beyond MVP.

For detailed database schema documentation, migration files, and performance considerations, see:
- [Database Schema Documentation](./Database%20Schema%20Documentation.md)

# API Structure

This project implements a dual-mode API structure to support both the HTMX-based web interface and future mobile clients. The API is designed to serve HTML fragments for HTMX requests and JSON for mobile/API clients.

## Core User Flow

The MVP focuses on the essential "Read → Log → See Progress" flow with these key user interactions:

1. **User Authentication**: Register, login, and logout functionality
2. **Reading Log**: Create and view reading logs
3. **Progress Visualization**: View streak and reading history calendar
4. **Advanced Statistics**: Track longest streak and Bible books completed

### Web Routes (HTMX-Compatible)

HTMX expects HTML fragments in responses, not JSON objects. These routes are designed for the web interface.

### Authentication Routes
GET /register
- Returns: HTML registration form

POST /register
- Content-Type: multipart/form-data
- Params: name, email, password, password_confirmation
- Returns: Redirect with HTML response

GET /login
- Returns: HTML login form

POST /login
- Content-Type: multipart/form-data
- Params: email, password
- Returns: Redirect with HTML response

POST /logout
- Returns: Redirect with HTML response

### Reading Log Routes
GET /logs
- Returns: HTML fragment of reading logs
- Used with: hx-get="/logs" hx-target="#logs-container"

POST /logs
- Content-Type: multipart/form-data
- Params: date_read, passage_text, notes_text
- Returns: HTML fragment (success message or updated list)
- Used with: hx-post="/logs" hx-swap="outerHTML" or similar

### Progress Visualization Routes
GET /streak
- Returns: HTML fragment with streak information
- Used with: hx-get="/streak" hx-target="#streak-display"

GET /calendar
- Returns: HTML fragment with calendar visualization
- Used with: hx-get="/calendar?month=5&year=2025" hx-target="#calendar-display"

# Statistics Routes
GET /stats/books
- Returns: HTML fragment with book completion stats (from the BookProgress table)
- Used with: hx-get="/stats/books" hx-target="#book-progress"

GET /stats/summary
- Returns: HTML fragment with summary statistics
- Used with: hx-get="/stats/summary" hx-target="#stats-summary"

# Post-MVP Statistics Routes (Phase 2)
GET /stats/weekly
- Returns: HTML fragment with weekly reading statistics
- Used with: hx-get="/stats/weekly" hx-target="#weekly-stats"

## JSON API Routes (Mobile/API Clients)

These endpoints serve JSON responses for mobile apps and other API clients.

### Authentication Endpoints (Sanctum)
POST /api/register
- Content-Type: application/json
- Params: name, email, password, password_confirmation
- Returns: user object with Sanctum API token

POST /api/login
- Content-Type: application/json
- Params: email, password
- Returns: user object with Sanctum API token (plainTextToken)

POST /api/logout 
- Requires: Sanctum Authentication
- Returns: success message

### Reading Log Endpoints
GET /api/logs
- Requires: Authentication
- Optional Query Params: from_date, to_date
- Returns: array of reading log objects

POST /api/logs
- Requires: Authentication
- Content-Type: application/json
- Params: date_read, passage_text, notes_text
- Returns: created reading log object

### Progress Visualization Endpoints
GET /api/streak
- Requires: Authentication
- Returns: streak object (current_streak, longest_streak)

GET /api/calendar
- Requires: Authentication
- Optional Query Params: year, month
- Returns: array of dates with reading logs

# Statistics Endpoints
GET /api/stats/books
- Requires: Authentication
- Returns: array of books with read status and completion percentage (from the BookProgress table)

GET /api/stats/summary
- Requires: Authentication
- Returns: summary statistics (total_logs, longest_streak, books_started, books_completed)

# Post-MVP Statistics Endpoints (Phase 2)
GET /api/stats/weekly
- Requires: Authentication
- Optional Query Params: year, week_number
- Returns: weekly reading statistics and day-by-day breakdown

## Future Endpoints (Post-MVP)

The following endpoints will be implemented in later phases after the MVP launch:

```
# Reading Plans Endpoints (Phase 2)
GET /api/plans
GET /api/plans/{id}
POST /api/plans/{id}/subscribe
GET /api/plans/active
PUT /api/plans/{id}/progress

# Goals Endpoints (Phase 2)
GET /api/goals
POST /api/goals
GET /api/goals/{id}
PUT /api/goals/{id}
DELETE /api/goals/{id}

# Achievements Endpoints (Phase 3)
GET /api/achievements
GET /api/user/achievements
```

## Core Feature Implementation

### Bible Passage Input

The Bible passage input is a critical component of the reading log functionality. For the MVP, we'll implement a simplified two-step structured selector approach to simplify the user experience and ensure data integrity.

#### Structured Bible Passage Selector

The UI will provide a simplified two-step structured selector for the MVP:

1. **Book Selection**:
   - Dropdown menu of all 66 books of the Bible
   - Example: Genesis, Exodus, ... Revelation

2. **Chapter Selection**:
   - Numeric input or dropdown that dynamically updates based on the selected book
   - Constrained to valid chapter numbers for the selected book
   - Example: John has 21 chapters, so the range would be 1-21

**Note:** For the MVP, reading logs will be restricted to whole chapters only to simplify the implementation. Verse-level tracking can be added in future iterations.

#### Implementation Logic

1. **Bible Reference Data**:
   - Maintain a static configuration file with data for all 66 Bible books
   - Implementation as a PHP config file for optimal performance:
   ```php
   // config/bible.php - Configuration only (structural data)
   return [
       'books' => [
           1 => [
               'id' => 1,
               'chapters' => 50,
               'testament' => 'old'
           ],
           2 => [
               'id' => 2,
               'chapters' => 40,
               'testament' => 'old'
           ],
           // ... remaining 64 books
       ],
       'testaments' => [
           'old' => ['range' => [1, 39]],
           'new' => ['range' => [40, 66]]
       ],
       'default_locale' => 'en',
       'supported_locales' => ['en', 'fr']
   ];
   ```
   - Create specialized services to access this data efficiently:
   ```php
   // Service for Bible book operations
   class BibleReferenceService
   {
       public function getBibleBook(int|string $identifier, ?string $locale = null): ?array
       {
           $locale = $locale ?? 'en';
           
           // Get book by ID or name with proper translation support
           if (is_numeric($identifier)) {
               $bookId = (int) $identifier;
               if (isset(config('bible.books')[$bookId])) {
                   $book = config('bible.books')[$bookId];
                   $book['name'] = __("bible.books.{$bookId}", [], $locale);
                   return $book;
               }
           }
           
           // Search by localized name
           foreach (config('bible.books') as $bookId => $book) {
               $translatedName = __("bible.books.{$bookId}", [], $locale);
               if (strtolower($translatedName) === strtolower($identifier)) {
                   $book['name'] = $translatedName;
                   return $book;
               }
           }
           
           return null;
       }

       public function validateBibleReference(int $bookId, int $chapter): bool
       {
           if (!$this->validateBookId($bookId)) {
               return false;
           }
           
           return $this->validateChapterNumber($bookId, $chapter);
       }
       
       public function formatBibleReference(int $bookId, int $chapter, ?string $locale = null): string
       {
           $bookName = __("bible.books.{$bookId}", [], $locale ?? 'en');
           return "{$bookName} {$chapter}";
       }
   }
   ```
   - This approach is more efficient than a database table since Bible data is fixed and unchanging
   - Services are highly testable and can be reused across controllers

   **Translation System Architecture**:
   ```php
   // lang/en/bible.php - English translations
   return [
       'books' => [
           1 => 'Genesis',
           2 => 'Exodus',
           // ... all 66 books
       ],
       'testaments' => [
           'old' => 'Old Testament',
           'new' => 'New Testament'
       ]
   ];
   
   // lang/fr/bible.php - French translations
   return [
       'books' => [
           1 => 'Genèse',
           2 => 'Exode',
           // ... all 66 books
       ],
       'testaments' => [
           'old' => 'Ancien Testament',
           'new' => 'Nouveau Testament'
       ]
   ];
   ```
   
   **Separation of Concerns**:
   - Configuration data (`config/bible.php`): Book structure, chapter counts, testament organization
   - Translation data (`lang/{locale}/bible.php`): Localized book names and labels
   - Business logic (`BibleReferenceService`): Book operations, validation, formatting
   - Fallback system: Service works in both Laravel and standalone contexts

2. **Dynamic Selection Logic**:
   - When a book is selected, dynamically populate the chapter dropdown with valid chapter numbers
   - Ensure only valid chapter numbers can be selected based on the chosen book

3. **Reference Formatting**:
   - Format user selections into standardized reference strings in the format "Book Chapter"
   - Example: "John 3" or "Genesis 1"

#### Benefits of Structured Selector Approach

1. **Data Integrity**: Ensures all logged passages are valid Bible references
2. **Simplified UX**: No need for users to remember exact formatting
3. **Reduced Error Rate**: Eliminates typos and invalid references
4. **Consistent Data Format**: Makes analysis and visualization easier
5. **Future-Proof**: Enables potential Bible API integration in later phases

In future iterations, we can add a free-form text input option with validation for users who prefer that input method.

### Responsive Navigation Architecture

The application implements a dual-navigation system optimized for different device types and user patterns:

#### Mobile Navigation Strategy

1. **Bottom Tab Navigation**:
   - **3-tab layout**: Dashboard, History, Profile/Settings
   - **Touch-optimized**: Minimum 44px x 44px touch targets
   - **Fixed positioning**: Always visible for consistent access
   - **Active state indicators**: Visual feedback for current page

2. **Primary Action Strategy - Hybrid Approach**:
   - **Mobile (< 1024px)**: Floating Action Button (FAB) for "Log Reading"
     - **Strategic positioning**: Bottom-right, above bottom navigation
     - **Accessibility**: Large enough for easy thumb access
     - **Visual prominence**: Uses accent color (#FF9933) for attention
   - **Desktop (≥ 1024px)**: Header Action Button for "Log Reading"
     - **Strategic positioning**: Top-right of content header
     - **Enhanced accessibility**: Full-sized button with icon and text
     - **Contextual placement**: Near page content for better UX

#### Desktop Navigation Strategy

1. **Sidebar Navigation**:
   - **Always visible**: 256px fixed width sidebar
   - **Comprehensive navigation**: All main sections with icons and labels
   - **User context**: Profile section with avatar and quick logout
   - **Active state**: Clear visual indicators for current page

2. **Content Layout**:
   - **Two-column approach**: 70% main content, 30% supporting information
   - **Responsive grid**: Adapts to different screen sizes
   - **Contextual sidebar**: Shows relevant statistics and quick actions

#### Implementation Details

```php
// Layout structure in authenticated.blade.php
// Mobile: Bottom nav (3 tabs) + FAB
// Desktop: Sidebar + header with action button + main content area + secondary sidebar

// Navigation state management with Alpine.js
<div x-data="{ mobileMenuOpen: false }" class="flex h-screen">
    <!-- Desktop Sidebar (hidden on mobile) -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64">
        <!-- Navigation items with active state detection -->
        @if(request()->routeIs('dashboard'))
            <a class="bg-primary/10 text-primary border-r-2 border-primary">
        @endif
    </aside>
    
    <!-- Desktop Header with Action Button -->
    <header class="hidden lg:block">
        <div class="flex items-center justify-between">
            <div>
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p>@yield('page-subtitle', 'Track your Bible reading journey')</p>
            </div>
            <!-- Log Reading Button -->
            <a href="{{ route('logs.create') }}" class="bg-accent hover:bg-accent/90">
                Log Reading
            </a>
        </div>
    </header>
    
    <!-- Mobile bottom navigation -->
    <nav class="lg:hidden fixed bottom-0">
        <!-- 3 navigation tabs -->
    </nav>
    
    <!-- Floating Action Button (mobile only) -->
    <a href="{{ route('logs.create') }}" 
       class="lg:hidden fixed bottom-20 right-4">
        <!-- FAB for mobile devices -->
    </a>
</div>
```

#### Design Rationale

1. **User Journey Optimization**: Primary action placement recognizes that "Log Reading" is the most frequent daily action
2. **Platform-Specific UX**: 
   - **Mobile**: FAB follows mobile design patterns with thumb-friendly positioning
   - **Desktop**: Header button provides better accessibility and visual hierarchy
3. **Progressive Enhancement**: Desktop layout expands functionality while maintaining mobile-first approach
4. **Accessibility**: Proper touch targets, keyboard navigation, and clear labeling on all platforms
5. **Visual Hierarchy**: Consistent accent color maintains prominence while adapting to platform conventions
6. **Contextual Placement**: Desktop header button is closer to content, reducing cognitive load

#### Route Structure

The navigation system expects these core routes:
- `dashboard` - Main landing page with streak and calendar
- `history` - Reading log history and filtering
- `profile` - User settings and account management  
- `logs.create` - Reading log entry form (FAB target)
- `logout` - Authentication termination

### Advanced Statistics Implementation

The Advanced Statistics feature provides motivating metrics about Bible reading progress. For the MVP, we'll focus on a streamlined set of high-value statistics that encourage habit formation while keeping implementation simple.

#### MVP Reading Statistics Logic

1. **Core Statistics Calculation**:
   - Current streak (using the streak calculation logic with 1-day grace period)
   - All-time longest streak
   - Total chapters read (count of reading logs)
   - Total books started vs. completed
   - Number of days with reading activity
   
      ```php
   // Service for user statistics
   class UserStatisticsService
   {
       public function __construct(
           private ReadingLogService $readingLogService
       ) {}

       public function getDashboardStats(User $user): array
       {
           $currentStreak = $this->readingLogService->getCurrentStreak($user);
           $longestStreak = $this->readingLogService->getLongestStreak($user);
           
           $readingStats = $this->getReadingStats($user);
           $bookStats = $this->getBookProgressStats($user);
           
           return [
               'current_streak' => $currentStreak,
               'longest_streak' => $longestStreak,
               'total_chapters' => $readingStats['total_chapters'],
               'reading_days' => $readingStats['reading_days'],
               'books_started' => $bookStats['books_started'],
               'books_completed' => $bookStats['books_completed'],
           ];
       }
       
       private function getReadingStats(User $user): array
       {
           // Calculate reading statistics
           return [
               'total_chapters' => $user->readingLogs()->count(),
               'reading_days' => $user->readingLogs()
                   ->selectRaw('DATE(date_read) as reading_date')
                   ->distinct()
                   ->count()
           ];
       }
   }
   ```

2. **Post-MVP Statistics** (to be implemented after initial launch):
   - Weekly reading summaries
   - Reading consistency percentage
   - Day-of-week patterns
   - Trend analysis

3. **Book Completion Tracking**:
   - **Performance-Optimized Approach:** 
     - Implement a denormalized `book_progress` table for efficient tracking:
       ```
       ┌────────────────────┐
       │   book_progress    │
       ├────────────────────┤
       │ id (PK)            │
       │ user_id (FK)       │
       │ book_id            │ (from static Bible config)
       │ book_name          │
       │ total_chapters     │
       │ chapters_read      │ (JSON array or serialized data)
       │ completion_percent │
       │ is_completed       │ (boolean)
       │ last_updated       │
       └────────────────────┘
       ```
     - Update this table incrementally with each new reading log:
       - When user logs a chapter (e.g., "Genesis 1"):
         1. Parse the passage to extract book name and chapter
         2. Look up the book_id from the static Bible configuration (e.g., Genesis = 1)
         3. Find or create a BookProgress record for this user and book_id
         4. Add the chapter to the chapters_read array if not already present
         5. Recalculate completion_percentage based on chapters_read vs total_chapters
       - This approach avoids scanning all reading logs for statistics calculations
   - Consider a book "started" when at least one chapter has been read
   - Consider a book "completed" when all chapters have been read at least once

4. **Bible Reading Progress**:
   - Initialize a data structure for all 66 books of the Bible
   - For each book, track:
     - Total chapters in the book
     - Which chapters have been read
     - Completion percentage
   - Update this structure as new readings are logged

#### MVP Visualization Approach

1. **Book Completion Grid**:
   - Simple grid or list showing all 66 Bible books in canonical order
   - Basic color-coding to indicate completion status:
     - Not started: Light gray
     - In progress: Blue
     - Completed: Green
   - Display completion percentage for each book

2. **Statistics Dashboard**:
   - Focus on key motivational metrics:
     - Current streak counter with visual indicator (e.g., flame icon)
     - All-time longest streak
     - Total chapters read
     - Books started vs. completed count
   - Simple, clean design that highlights achievements

3. **Calendar View**:
   - Monthly calendar showing reading activity
   - Color-coded days to indicate reading activity
   - Simple hover/click to see what was read on a specific day

#### Key Statistics Features for MVP

1. **Current Streak Tracking**: Shows the user's current consecutive days of reading (with 1-day grace period)
2. **Longest Streak Tracking**: Records and displays the user's best reading streak ever achieved
3. **Book Completion Tracking**: Shows which books of the Bible have been started and which are fully read
4. **Basic Reading Summary**: Shows total chapters read, books started, and books completed
5. **Calendar Visualization**: Provides a monthly view of reading activity

**Post-MVP Statistics Features** (Phase 2):
1. **Weekly Analysis**: Week-by-week performance metrics and trends
2. **Reading Patterns**: Analysis of reading consistency and preferred days
3. **Advanced Visualizations**: Heat maps and progress charts
4. **Personalized Insights**: Recommendations based on reading patterns

### Streak Calculation Logic

Streak calculation is a critical feature of the MVP. Here's the general approach:

#### Current Streak Calculation

1. **Data Collection**:
   - Retrieve all reading logs for the user
   - Extract and normalize the dates to avoid timezone issues

2. **Streak Logic**:
   - Check if the user has read today or yesterday
   - If neither, current streak is 0
   - If either day has a reading, begin counting consecutive days backwards
   - Count how many consecutive days in the past have readings

3. **Grace Period**:
   - Implement a 1-day grace period
   - Streak continues if user reads either today OR yesterday
   - This makes the habit more sustainable and accounts for timezone edge cases

#### Longest Streak Calculation

1. **Historical Analysis**:
   - Analyze all user reading dates chronologically
   - Find the longest sequence of consecutive calendar days
   - Store this value for display and badges/achievements

2. **Edge Case Handling**:
   - Account for non-sequential order of data entry
   - Handle timezone shifts
   - Process date gaps appropriately

#### Key Streak Calculation Features

1. **Timezone Handling**: All dates normalized to the start of day to avoid timezone issues
2. **Grace Period**: The streak continues if the user reads today OR yesterday (1-day grace period)
3. **Performance**: Uses efficient date comparison and caching for quick calculations
4. **Historical Data**: Tracks both current streak and all-time longest streak

### Implementation Strategy

The application uses Laravel Sanctum for authentication and implements content negotiation to serve appropriate responses based on the client:

```php
// Web Routes (using Sanctum's cookie auth)
public function index(Request $request)
{
    // Authentication already handled by Sanctum via cookies
    $logs = ReadingLog::where('user_id', auth()->id())->get();
    
    if ($request->header('HX-Request') || !$request->expectsJson()) {
        // HTMX request or regular browser request - return HTML fragment
        return view('partials.logs', ['logs' => $logs]);
    }
    
    // API request - return JSON
    return response()->json($logs);
}

// API token auth for mobile clients
public function login(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    // Generate Sanctum token for mobile clients
    $token = $request->user()->createToken('mobile-app')->plainTextToken;
    return response()->json([
        'user' => $request->user(),
        'token' => $token
    ]);
}
```

This approach allows the same backend logic to serve both the HTMX-based web interface and mobile clients with appropriate response formats.

## Tech Stack Justification

### Backend: Laravel 12

**Strengths for this project:**
- **Rapid Development**: Laravel's elegant syntax and robust features allow for quick development of core functionality like authentication, database operations, and API endpoints.
- **Eloquent ORM**: Simplifies database interactions, which will be essential for tracking reading logs, streaks, and user progress.
- **Queue System**: Perfect for handling background tasks like sending email reminders without impacting user experience.
- **Authentication System**: Laravel Sanctum for dual-mode authentication, supporting both cookie-based sessions for the HTMX frontend and token-based auth for future mobile APIs.
- **Testing Tools**: Testing frameworks like Pest make it easy to implement a test-driven approach from the start.

### Database: PostgreSQL (via Laravel Cloud)

**Strengths for this project:**
- **Reliability**: Well-established, ACID-compliant database with excellent data integrity.
- **JSON Support**: Native JSON column types ideal for storing flexible data like chapter tracking in BookProgress table.
- **Advanced Querying**: Powerful querying capabilities for complex streak calculations and data analytics.
- **Managed Platform**: Laravel Cloud provides automated backups, scaling, and maintenance.

### Frontend (Web): HTMX + Alpine.js

**Strengths for this project:**
- **Server-Driven Architecture**: HTMX enables server-driven state management with HTML fragments as the primary data format.
- **Minimal Client Logic**: Alpine.js provides "sprinkles of interactivity" for local UI state without complex state management.
- **Progressive Enhancement**: Works well even with limited JavaScript support.
- **Performance**: Lightweight compared to full frontend frameworks, leading to faster page loads.
- **Seamless Navigation**: Content loading patterns provide app-like experience without full page reloads.

**Implementation Details:**
- **HTMX**: Handles server communication, form submissions, HTML fragment updates, and event triggering
- **Alpine.js**: Manages local UI state (dropdowns, modals), client-side validation, and reactive data binding
- **Integration**: Both technologies work together seamlessly, with HTMX handling server interactions and Alpine managing client-side enhancements

### Zero-Duplication Architecture Pattern

**Core Principle**: Each UI component exists in exactly one place to prevent HTML duplication across HTMX views.

#### **Component Architecture:**
```
resources/views/
├── partials/
│   ├── {feature}-content.blade.php      # Shared content components
│   ├── {feature}-page.blade.php         # HTMX page containers  
│   ├── header-update.blade.php          # Parameterized shared components
│   └── {feature}-sidebar.blade.php      # Feature-specific reusable components
├── {feature}/
│   └── index.blade.php                  # Main views (use @include for components)
```

#### **HTMX Implementation Standards:**
```blade
// Page navigation (layout changes)
hx-get="{{ route('feature.index') }}" 
hx-target="#page-container"
return view('partials.feature-page')

// Content updates (same layout)  
hx-get="{{ route('feature.action') }}"
hx-target="#main-content"
return view('partials.feature-content')

// Parameterized components
@include('partials.header-update', [
    'title' => 'Page Title',
    'subtitle' => 'Optional description'
])
```

#### **Component Creation Process:**
1. Create shared content partial first
2. Create HTMX page container using `@include` statements
3. Use parameterized includes for reusable elements
4. Main view includes shared components
5. Controller supports both HTMX and direct access patterns

For detailed implementation patterns, see:
- [HTMX Implementation Guide](./HTMX%20Implementation%20Guide.md)
- [Alpine.js Component Guide](./Alpine.js%20Component%20Guide.md)

### Mobile: Native Swift (iOS) & Kotlin (Android)

**Strengths for this project:**
- **Performance**: Native development provides the best performance for mobile apps.
- **Platform Integration**: Better access to device features like notifications and offline storage.
- **User Experience**: Follows platform-specific design patterns for more intuitive UX.
- **Shared Backend**: Mobile apps will consume the same Laravel API endpoints as the web interface.

### Deployment: Laravel Cloud

**Strengths for this project:**
- **Integrated Platform**: Purpose-built for Laravel applications with optimized performance.
- **Managed Database**: Serverless PostgreSQL with automatic scaling and point-in-time recovery.
- **Simplified DevOps**: Reduces operational complexity with automated deployments and monitoring.
- **Cost Efficiency**: Pay-as-you-go pricing model with automatic scaling based on usage.

## Authentication

The application uses **Laravel Fortify** as a frontend-agnostic authentication backend, providing robust authentication logic while maintaining full control over the standard Laravel + Blade frontend.

### Web Authentication (Laravel Fortify + Standard Laravel Forms)

1. **Laravel Fortify Backend**:
   - Handles all authentication logic, routes, and controllers
   - Provides registration, login, logout, password reset, and email verification
   - Session-based authentication for web users
   - Secure, HttpOnly cookies with CSRF protection
   - Customizable actions for registration and password reset logic

2. **Standard Laravel Frontend** (MVP Simplified):
   - Custom Blade views styled to match application design
   - Standard HTML forms with `method="POST"` submissions
   - Laravel's built-in validation error display with `@if ($errors->any())`
   - Standard Fortify redirect handling for authentication flows
   - **Architectural Decision**: Simplified from HTMX to reduce complexity (~300 lines of JavaScript)

3. **Configuration**:
   - Fortify service provider configured in `FortifyServiceProvider`
   - Custom view responses defined for login, registration, and password reset
   - User model integration with Fortify's authentication system
   - Route middleware protection for authenticated areas
   - RouteServiceProvider HOME constant for post-authentication redirects

### API Authentication (Post-MVP)

1. **Laravel Sanctum Integration**:
   - Fortify works seamlessly with Sanctum for API authentication
   - Token-based authentication for mobile clients
   - Ability to scope tokens to specific abilities
   - Support for mobile apps (iOS/Android)

2. **Security Considerations**:
   - Token expiration policies
   - Rate limiting for authentication endpoints
   - IP-based blocking for suspicious activity
   - Fortify's built-in security features and validation

### Benefits of Fortify Approach

1. **No Frontend Conflicts**: Fortify provides only backend logic, allowing full standard Laravel frontend control
2. **Laravel 12 Native**: Fully supported and maintained authentication solution
3. **Security**: Enterprise-grade authentication features with regular security updates
4. **Simplicity**: Standard Laravel patterns reduce complexity and maintenance overhead
5. **MVP-Focused**: Proven authentication flows without client-side complexity
6. **Future-Proof**: Easy integration with mobile APIs and social authentication in later phases

## Internationalization

The application will support both English and French languages from the MVP launch, with a focus on serving users in Quebec and other francophone regions.

### Implementation Approach

1. **Laravel Localization**:
   - Use Laravel's built-in localization system (`resources/lang/` directory structure)
   - Language files for all UI strings and messages
   - Language middleware to detect and set preferred language

2. **Bible Reference Handling**:
   - Bible book names stored in both English and French
   - Support for Bible book name input in either language
   - Display of Bible references in user's preferred language

3. **User Experience**:
   - Language toggle accessible from any page
   - Language preference stored in user profile
   - Default language detection based on browser settings

4. **Data Considerations**:
   - User-generated content (notes) remains in original input language
   - Dates and times formatted according to locale preferences
   - Error messages and system notifications translated

### Implementation Details

1. **Technical Implementation**:
   - Translation files structured by feature area
   - Use of translation keys rather than hard-coded strings
   - Configuration for date/time localization

2. **Bible Reference Configuration**:
   - Extend the static configuration file (config/bible.php) to include:  
     ```php
     [
       'id' => 1,
       'name' => [
         'en' => 'Genesis',
         'fr' => 'Genèse'
       ],
       'chapter_count' => 50
     ]
     ```
   - Bible reference services will handle lookups in both languages

## Security Considerations

1. **Authentication**: Using Laravel Sanctum for secure authentication:
   - Cookie-based sessions for standard Laravel web interface
   - Token-based API authentication for mobile clients
   - Built-in CSRF protection for web forms

2. **Data Protection**:
   - All PII (Personally Identifiable Information) will be encrypted at rest.
   - HTTPS enforced for all communications.
   - Rate limiting implemented on all API endpoints to prevent abuse.

3. **Input Validation**:
   - All user inputs validated server-side using Laravel's validation system.
   - Parameterized queries via Eloquent ORM to prevent SQL injection.

4. **API Security**:
   - Token expiration policies.
   - Scope-based permission system for API endpoints.
   - CORS policies properly configured for web and mobile clients.

5. **Database Security**:
   - Least privilege database user permissions.
   - Regular security audits and updates.
   - Automated backups with encryption.

## Scalability Considerations

1. **Database**:
   - Denormalized BookProgress table for efficient book completion tracking without scanning all reading logs.
   - Indexed queries for common operations (streak calculations, calendar views).
   - Caching layer for frequently accessed data (current streak, reading plans).
   - Potential sharding strategy for user data as user base grows.

2. **Application**:
   - Stateless API design to support horizontal scaling.
   - Queue workers for background tasks (email sending, achievement calculations).
   - Caching of expensive calculations (streaks, statistics).

3. **Infrastructure**:
   - Ability to scale web servers independently of background workers.
   - CDN integration for static assets.
   - Monitoring and alerting to identify bottlenecks early.

## Testing Strategy

A pragmatic testing approach focused on essential functionality for the Bible Reading Habit Builder MVP, emphasizing core user workflows and business-critical validations.

### MVP Testing Philosophy

The testing strategy prioritizes **essential verification over comprehensive coverage** for the initial MVP release. This approach focuses developer time on shipping core functionality while ensuring reliability of critical features like streak calculation and book completion tracking.

### Core Testing Areas

1. **Database Compatibility Verification** (2-3 hours):
   - **Migration Compatibility**: Ensure migrations run successfully on both SQLite (local) and PostgreSQL (production)
   - **Essential Model Validations**: Verify core business rules (book_id ranges, email uniqueness, completion percentages)
   - **Critical Constraints**: Test foreign keys, unique constraints, and JSON column functionality
   - **Basic Integration**: Confirm core user workflow (register → log reading → view progress) works on both databases

2. **Unit Tests** (Essential Only):
   - **Streak Calculation Logic**: Test current streak calculation with 1-day grace period
   - **Bible Reference Validation**: Test book_id (1-66) and chapter number validation
   - **Book Progress Updates**: Test completion percentage calculation when chapters are logged

3. **Feature Tests** (Core User Journey):
   - **Authentication Flow**: Registration and login functionality
   - **Reading Log Creation**: End-to-end reading log submission with book progress updates
   - **Dashboard Statistics**: Basic statistics display and calculation accuracy

4. **Browser Tests** (Critical Path Only):
   - **Standard Form Submissions**: Authentication and core form functionality
   - **HTMX Interactions**: Reading log submissions and dashboard updates (non-auth features)
   - **Mobile Responsiveness**: Core functionality on mobile devices

### Testing Tools

1. **PHPUnit**:
   - Primary testing framework for unit and feature tests
   - Custom assertions for domain-specific validations

2. **Laravel Dusk**:
   - Browser testing for critical user flows
   - Verification of standard form submissions and HTMX interactions (non-auth features)

3. **JMeter/k6**:
   - Load testing for performance-critical endpoints
   - Simulation of concurrent users

4. **Laravel Telescope**:
   - Development-time monitoring of queries, cache, and requests
   - Identification of N+1 query issues

### Test Data Strategy

1. **Factories and Seeders**:
   - Create realistic test data with Laravel factories
   - Seed specific scenarios for edge case testing
   - Generate large datasets for performance testing

2. **Test Fixtures**:
   - Predefined reading patterns for streak testing
   - Various book completion scenarios

### Continuous Integration

1. **Automated Test Runs**:
   - Run tests on every pull request
   - Nightly full test suite execution

2. **Code Coverage**:
   - Track test coverage for critical components
   - Aim for 90%+ coverage of core business logic

3. **Static Analysis**:
   - PHPStan/Psalm for static code analysis
   - Laravel Pint for code style enforcement

### Testing Priorities

1. **Critical Path Testing** (MVP Focus):
   - Core user workflow: register → log reading → view progress
   - Streak calculation with 1-day grace period
   - Book progress updates when chapters are logged
   - Basic authentication flows

2. **Essential Validation** (Business Rules):
   - Bible reference validation (book_id 1-66, valid chapters)
   - Model constraints (email uniqueness, completion percentages)
   - Database compatibility between SQLite and PostgreSQL

### Post-MVP Testing Expansion

Future testing phases will include:
- **Comprehensive Edge Case Testing**: Timezone handling, date boundaries, concurrent updates
- **Performance Testing**: Large dataset handling, query optimization
- **Advanced Integration Testing**: Complex HTMX interactions, full API coverage
- **Load Testing**: Multi-user scenarios and scalability validation

This simplified testing strategy ensures that the application's core functionality is reliable while allowing rapid iteration and feature development during the MVP phase. Comprehensive testing will be implemented as the application grows and user feedback validates the core concept.

## Future Considerations

### Additional Features for Future Releases

1. **Social Authentication (Laravel Socialite)**: OAuth integration with Google, Facebook, and Apple Sign-In for streamlined user onboarding
2. **Reading Plans**: Structured reading plans for different purposes (e.g., chronological, thematic, devotional)
3. **Notes and Highlights**: Allow users to add personal notes and highlight verses
4. **Social Sharing**: Share reading progress or insights with friends
5. **Multiple Translations**: Support for different Bible translations
6. **Audio Bible**: Integration with audio Bible APIs via dedicated services
7. **Offline Mode**: Full offline functionality for mobile apps

### Advanced Reading Goals System

A comprehensive goal-setting system will empower users to create and track personalized Bible reading objectives, deeply integrated with the advanced statistics feature:

#### Goal Types

1. **Daily Reading Goals**:
   - Specific chapter count per day (e.g., "3 chapters daily")
   - Time-based reading (e.g., "15 minutes of Bible reading daily")
   - Specific time of day (e.g., "Morning reading before 8 AM")
   - Verse count targets (e.g., "30 verses minimum per day")
   - Consistent daily reading (e.g., "Read every day")

2. **Habit-Forming Goals**:
   - Consistency targets (maintain 80%+ weekly completion rate)
   - Reading at specific times of day
   - Duration-based reading sessions
   - Paired activities (e.g., "Read before prayer time")

#### Integration with Advanced Statistics

1. **Weekly Goal Performance Summary**:
   - Weekly dashboard showing daily goal success rate (e.g., "5/7 daily goals met this week")
   - Weekly completion percentage highlighting overall performance
   - Heat map visualization showing which days goals were met
   - Visual indicators of daily consistency within each week
   - Trend analysis comparing goal performance across weeks
   - Adaptive recommendations based on weekly patterns

2. **Achievement Visualization**:
   - Calendar view with daily and weekly goal completion indicators
   - Streak indicators for consecutive days meeting daily goals
   - Progress bars showing percentage toward each active goal
   - Weekly email/notification summarizing daily and weekly goal progress
   - Historical comparison of goal achievement rates
   - Week-over-week comparison of daily goal success rates

3. **Performance Insights**:
   - Analysis of which goals are most attainable for the user
   - Identification of patterns in successful vs. missed goals
   - Suggestions for realistic goal adjustments
   - Correlation between goal types and reading consistency

#### Technical Implementation

1. **Data Model Extensions**:
   ```
   ┌────────────────────┐       ┌────────────────────┐       ┌────────────────────┐
   │      Goals         │       │    GoalProgress    │       │   ReadingLogs      │
   ├────────────────────┤       ├────────────────────┤       ├────────────────────┤
   │ id                 │       │ id                 │       │ id                 │
   │ user_id            │◄──────┤ goal_id            │       │ user_id            │
   │ title              │       │ date               │───────┤ date_read          │
   │ description        │       │ period_type        │       │ passage_text       │
   │ type               │       │ period_value       │       └────────────────────┘
   │ target_value       │       │ target_value       │
   │ frequency          │       │ actual_value       │
   │ period_type        │       │ is_achieved        │
   │ start_date         │       │ feedback_given     │
   │ end_date           │       └────────────────────┘
   │ recurrence_pattern │
   │ is_active          │
   └────────────────────┘
   ```
   
   The `period_type` field in both tables supports different time periods (daily, weekly, monthly) enabling tracking at various granularities.

2. **UI Components**:
   - Goal creation wizard with templates
   - Weekly review dashboard showing goal performance
   - Goal adjustment interface based on past performance
   - Notification preferences for goal reminders

3. **Backend Services & Business Logic**:
- `EvaluateWeeklyGoalsJob`: Weekly goal evaluation logic
- `CalculateAchievementsJob`: Achievement calculation engine
- `GenerateGoalRecommendationsFeature`: Recommendation system for goal adjustments
- `ScheduleProgressNotificationsJob`: Notification scheduler for progress updates

#### Benefits for Bible Reading Habit

1. **Personalization**: Users can create goals that match their specific spiritual journey
2. **Accountability**: Weekly progress checks maintain focus on commitments
3. **Gradual Growth**: Progressive goal difficulty as reading habits strengthen
4. **Celebration**: Recognition of achievements reinforces positive behavior

This system will use data from the advanced statistics feature to help users set realistic goals and track their progress effectively, creating a virtuous cycle where goals drive reading activity and statistics provide feedback on goal attainment.

### Micro-rewards System

A gamification-based micro-rewards system will enhance user engagement and habit formation. This feature will include:

#### Achievement Types

1. **Milestone Badges**:
   - **Book Completion**: Unique badge for each completed Bible book
   - **Chapter Milestones**: Recognition for reading 10, 50, 100, 500 chapters
   - **Consistency Awards**: Special badges for 7, 30, 90, 365-day streaks

2. **Reading Levels**:
   - Progression system from "Beginner" to "Scholar"
   - Level-up animations and congratulatory messages
   - Profile indicators showing current level

3. **Special Achievements**:
   - **Testament Completer**: Finishing the Old or New Testament
   - **Genre Master**: Reading all books of a particular genre (e.g., Epistles, Historical)
   - **Challenge Conqueror**: Completing special reading challenges

#### Implementation Approach

1. **Technical Architecture**:
   - `TrackUserActivityJob`: Monitor user activity for achievements
   - `AwardAchievementFeature`: Event-driven system to award achievements in real-time
   - `SendAchievementNotificationJob`: Alert users of new achievements

2. **Data Structure**:
   ```
   ┌───────────────────┐       ┌───────────────────┐       ┌───────────────────┐
   │   Achievements    │       │  UserAchievements │       │       Users       │
   ├───────────────────┤       ├───────────────────┤       ├───────────────────┤
   │ id                │       │ id                │       │ id                │
   │ name              │◄──────┤ achievement_id    │───────┤ user_id           │
   │ description       │       │ user_id           │       │ current_level     │
   │ icon_path         │       │ earned_at         │       │ experience_points │
   │ points_value      │       │ progress_data     │       └───────────────────┘
   │ requirements_json │       └───────────────────┘
   └───────────────────┘
   ```

3. **User Experience**:
   - Subtle, non-intrusive achievement notifications
   - Dedicated achievements page with visual progression
   - Optional social sharing of significant achievements

#### Benefits for Habit Formation

1. **Intrinsic Motivation**: Creates satisfaction from progress visualization
2. **Variable Rewards**: Mix of predictable and surprise achievements maintains interest
3. **Progression Feedback**: Clear indication of growth encourages continued engagement
4. **Milestone Celebration**: Recognizes and reinforces important accomplishments

This system will be designed to complement rather than overshadow the spiritual nature of Bible reading, with tasteful implementation that enhances the experience without gamifying it excessively.

### Personalized Reminders System

A smart notification system will help users maintain their reading habit through personalized, timely reminders:

#### Reminder Types

1. **Streak Protection Alerts**:
   - Gentle notifications when a user is at risk of breaking their streak
   - Customizable timing (morning, afternoon, evening)
   - Gradually increasing urgency as the day progresses
   - Option to snooze or disable

2. **Weekly Recap Statistics**:
   - End-of-week summary of reading activity
   - Highlights of achievements and progress
   - Comparison to previous weeks
   - Encouraging messages based on performance

3. **Smart Scheduling**:
   - Analysis of user's typical reading times
   - Adaptation to user's timezone and daily patterns
   - Machine learning model to predict optimal reminder times
   - Gradual adjustment based on user response patterns

#### Implementation Approach

1. **Technical Architecture**:
   - Background job system for scheduling reminders
   - User preference storage for notification settings
   - `AnalyzeOptimalTimingJob`: Determine optimal timing for reminders
   - Multi-channel delivery (push notifications, email, in-app)

2. **Data Requirements**:
   - User reading time patterns
   - Notification response history
   - Preference settings
   - Timezone and locale information

3. **Privacy Considerations**:
   - Clear opt-in/opt-out controls
   - Transparent data usage policies
   - Local processing of pattern data where possible
   - Minimal data collection principle

#### Benefits for Habit Formation

1. **Reduced Friction**: Timely reminders eliminate the need to remember
2. **Streak Protection**: Helps maintain momentum during busy periods
3. **Progress Awareness**: Weekly recaps reinforce the habit's importance
4. **Personalization**: Adapts to individual behavior patterns for maximum effectiveness

The reminder system will be designed to be helpful without becoming intrusive, with careful attention to frequency and tone to avoid notification fatigue.

### Database Configuration

The application uses different database configurations for local development and production environments to optimize for both developer experience and production performance.

#### Local Development (SQLite)
- **Database**: SQLite
- **File**: `database/database.sqlite`
- **Configuration**: 
  - Uses in-memory or file-based SQLite database for simplicity and speed
  - No need for separate database server installation
  - Ideal for development and testing environments
  - Configured in `.env` with `DB_CONNECTION=sqlite`
  - Database file is created automatically on first migration

#### Production (Laravel Cloud)
- **Platform**: Laravel Cloud
- **Database**: Managed PostgreSQL
- **Features**:
  - Automated backups and maintenance
  - High availability and scalability
  - Built-in monitoring and performance insights
  - Secure connections with TLS/SSL
  - Environment-based configuration via `.env` variables
  - Seamless integration with Laravel's deployment pipeline

For local development, SQLite is used for its simplicity and zero-configuration requirements:

- **Type**: SQLite (file-based)
- **Location**: `database/database.sqlite`
- **Configuration**:
  ```env
  DB_CONNECTION=sqlite
  DB_DATABASE=/absolute/path/to/project/database/database.sqlite
  ```
- **Benefits**:
  - No database server required
  - Fast development setup
  - Easy to reset and migrate
  - File-based for simple version control (though the file itself is in .gitignore)

#### Production (Laravel Cloud)

For production, the application uses Laravel Cloud's managed database platform:

- **Type**: Serverless PostgreSQL 17
- **Hosting**: Laravel Cloud (powered by Neon)
- **Configuration**:
  - Auto-configured by Laravel Cloud
  - Environment variables automatically injected
  - Connection pooling for high concurrency (up to 10,000 connections)
  - Hibernation support for cost optimization
- **Features**:
  - Automatic scaling (0.5-2 compute units)
  - Point-in-time recovery
  - Automated backups with encryption
  - High availability across multiple regions
  - Built-in monitoring and performance insights

#### Database Schema

The database schema follows these design principles:

1. **Denormalized Book Progress**:
   - Optimized for read performance
   - Separate `book_progress` table tracks completion status
   - Reduces need for complex joins in common queries

2. **User Data Isolation**:
   - All user data properly scoped to user accounts
   - Appropriate indexes for common query patterns
   - Soft deletes where appropriate

3. **Migrations**:
   - Version-controlled database changes
   - Rollback capabilities
   - Environment-specific seeders

#### Environment Variables

Key database-related environment variables:

```env
# Local Development (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Production (Laravel Cloud - auto-configured)
# DB_CONNECTION=pgsql
# DB_HOST=ep-xxx.cloud.laravel.cloud
# DB_PORT=5432
# DB_DATABASE=laravel
# DB_USERNAME=user
# DB_PASSWORD=password
# DB_SSL_MODE=require
```

### Caching Strategy

Based on performance assessment of the PR5 implementation, a robust caching strategy is essential for maintaining performance and scalability. Here's the detailed approach targeting specific bottlenecks:

#### Critical Performance Bottlenecks (Identified in PR5 Assessment)

1. **UserStatisticsService::getDashboardStatistics()** - Multiple uncached queries:
   - Current/longest streak calculations load all reading dates into PHP
   - Book progress summary loads all user progress records
   - Recent activity executes separate query
   - Reading summary executes 3 separate queries (count, oldest, latest)

2. **Streak Calculations** - Inefficient PHP processing:
   - `calculateCurrentStreak()` - fetches all dates, processes in PHP memory
   - `calculateLongestStreak()` - fetches all dates, processes in PHP memory
   - Should be converted to SQL window functions for better performance

3. **Calendar Data Generation** - PHP array processing:
   - `getCalendarData()` creates 365 array entries in PHP
   - Could be optimized with database aggregation

#### High-Impact Caching Implementation

**Primary Cache Targets with TTL Strategy:**
```php
// Dashboard statistics (recalculated only on new readings)
Cache::remember("user_dashboard_stats_{$userId}", 300, fn() => $this->getDashboardStatistics($user));

// Streak calculations (longer TTL since they change infrequently)
Cache::remember("user_current_streak_{$userId}", 900, fn() => $this->calculateCurrentStreak($user));
Cache::remember("user_longest_streak_{$userId}", 3600, fn() => $this->calculateLongestStreak($user));

// Bible reference data (static data, cache indefinitely)
Cache::remember("bible_books_{$locale}", 86400, fn() => $this->listBibleBooks(null, $locale));

// Calendar data (updated only on new readings for the year)
Cache::remember("user_calendar_{$userId}_{$year}", 1800, fn() => $this->getCalendarData($user, $year));
```

#### Cache Layers

1. **Application-Level Cache**:
   - User statistics and streak data (invalidated only on new reading logs)
   - Bible reference metadata (book/chapter/verse counts)
   - User reading history summaries
   - Calendar visualization data

2. **Database Query Cache**:
   - Frequently executed queries, particularly for statistics calculations
   - Results of complex aggregations
   - Book progress summaries

3. **HTTP Cache**:
   - Static assets with appropriate cache headers
   - API responses with ETags and conditional requests

#### Implementation Technologies

1. **Current Setup (Database Cache)**:
   - Default Laravel cache using database driver
   - Suitable for MVP launch and initial user load
   - Zero configuration required

2. **Production Scaling (Laravel Cloud + Redis)**:
   - Laravel KV Store (Redis API-compatible)
   - Upgrade path without code changes: `CACHE_STORE=redis`
   - Advanced features: pub/sub, atomic operations, complex data structures

#### Cache Invalidation Strategy

1. **Event-Based Invalidation** (Primary):
   ```php
   // Clear user stats cache on new reading log creation
   Cache::forget("user_dashboard_stats_{$userId}");
   Cache::forget("user_current_streak_{$userId}");
   Cache::forget("user_calendar_{$userId}_{$currentYear}");
   ```

2. **Time-Based TTL** (Secondary):
   - Dashboard stats: 5 minutes (frequent changes)
   - Streak calculations: 15 minutes (infrequent changes)
   - Bible reference data: 24 hours (static data)
   - Calendar data: 30 minutes (daily granularity)

3. **Selective Invalidation**:
   - Use cache tags to invalidate only affected portions
   - Maintain cache warmth for high-traffic users

#### Performance Monitoring

1. **Cache Hit Ratio Tracking**:
   - Target: >90% hit rate for dashboard statistics
   - Monitor cache effectiveness for each service method

2. **Query Performance Metrics**:
   - Measure query execution time before/after caching
   - Identify additional optimization opportunities

3. **Cache Size Monitoring**:
   - Monitor memory usage patterns
   - Implement automatic cleanup for stale entries

#### SQL Optimization Targets (Complementary to Caching)

- Convert streak calculations from PHP loops to SQL window functions
- Batch book progress updates during reading log creation
- Add composite indexes for calendar queries: `(user_id, date_read)`
- Optimize book progress aggregation queries

This caching strategy targets the specific performance bottlenecks identified in the current implementation and will be implemented incrementally, starting with the highest-impact optimizations first.

### Social Authentication Implementation Strategy (Phase 2)

When implementing social authentication with Laravel Socialite in Phase 2, the existing authentication architecture will require minimal changes:

#### Database Schema Extensions
- Add `provider`, `provider_id`, and `avatar_url` columns to existing users table
- Add composite unique index for social accounts (provider + provider_id)
- Maintain backward compatibility with existing email/password users

#### Service Layer Extensions
- Extend existing `UserService` with social authentication methods
- Implement `findOrCreateFromSocial()` method for OAuth user handling
- Support account linking for users who initially registered with email/password
- Handle profile data synchronization (name, email, avatar)

#### Standard Laravel Integration
- Social authentication will integrate seamlessly with existing standard Laravel forms
- Add social login buttons to existing authentication forms
- Maintain consistent user experience with standard Laravel redirects
- Support standard redirect-based authentication flows

#### Benefits for Bible Reading Community
- **Google**: Seamless integration for users already using Google services
- **Facebook**: Access to Christian communities and groups
- **Apple Sign-In**: Privacy-focused option for security-conscious users
- **Enhanced profiles**: Verified emails and profile pictures improve user experience

This implementation strategy ensures social authentication enhances rather than disrupts the existing user experience while maintaining the clean architecture established in the MVP.
