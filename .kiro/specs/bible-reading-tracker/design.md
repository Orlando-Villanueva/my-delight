# Bible Reading Habit Builder - Design Document

## Overview

The Bible Reading Habit Builder is architected as a modern Laravel web application using the Service Layer Pattern with HTMX for dynamic interactions. The system is designed around the core user flow: **Read → Log → See Progress**, providing immediate feedback and motivation through streak tracking and visual progress indicators.

**Key Design Principles:**
- **Service-Oriented Architecture**: Clean separation of concerns with dedicated service classes
- **HTMX-First Interactions**: Smooth, SPA-like experience without complex JavaScript
- **Mobile-First Responsive Design**: Optimized for both mobile and desktop usage patterns
- **Performance-Optimized**: Caching strategies and efficient database queries
- **Multilingual Ready**: Built-in support for English and French locales

## Architecture

### System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│                          Client Layer                               │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │  Web Browser  │    │   Mobile Web  │    │  Desktop Web  │        │
│  │  (HTMX +      │    │  (Touch UI)   │    │ (Sidebar UI)  │        │
│  │   Alpine.js)  │    │               │    │               │        │
│  └───────────────┘    └───────────────┘    └───────────────┘        │
└─────────────────────────────────────────────────────────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                       Application Layer                             │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │  Controllers  │    │   Middleware  │    │  Form Requests│        │
│  │ (Thin Layer)  │    │ (Auth, CORS)  │    │ (Validation)  │        │
│  └───────────────┘    └───────────────┘    └───────────────┘        │
└─────────────────────────────────────────────────────────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Service Layer                                   │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │ReadingLogSvc  │    │UserStatsSvc   │    │BibleRefSvc    │        │
│  │(Core Logic)   │    │(Analytics)    │    │(Validation)   │        │
│  └───────────────┘    └───────────────┘    └───────────────┘        │
└─────────────────────────────────────────────────────────────────────┘
           │                    │                    │
           ▼                    ▼                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         Data Layer                                  │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐        │
│  │   Models      │    │   Database    │    │   Cache       │        │
│  │ (Eloquent)    │    │ (PostgreSQL)  │    │ (Redis/File)  │        │
│  └───────────────┘    └───────────────┘    └───────────────┘        │
└─────────────────────────────────────────────────────────────────────┘
```

### Service Layer Design

The application implements a comprehensive Service Layer Pattern to encapsulate business logic and maintain clean separation of concerns:

#### ReadingLogService
**Purpose**: Core business logic for Bible reading management
- **Bible Reference Validation**: Validates book IDs (1-66) and chapter numbers against static configuration
- **Multi-Chapter Support**: Handles both single chapters and ranges (e.g., "John 1-3")
- **Book Progress Integration**: Automatically updates BookProgress records when chapters are logged
- **Streak Calculations**: Implements current and longest streak algorithms with 1-day grace period
- **Reading History**: Provides filtered and paginated reading log retrieval

#### UserStatisticsService
**Purpose**: Dashboard analytics and progress calculations
- **Dashboard Statistics**: Aggregates streaks, reading summaries, and book progress
- **Calendar Data Generation**: Creates GitHub-style heatmap data with 7 intensity levels
- **Performance Optimization**: Designed for caching integration with TTL-based invalidation
- **Recent Activity**: Deduplicates and formats recent reading sessions

#### BibleReferenceService
**Purpose**: Bible book management and localization
- **Static Configuration**: Loads from `config/bible.php` for optimal performance
- **Multilingual Support**: Integrates with Laravel's translation system
- **Validation Logic**: Comprehensive Bible reference validation
- **Chapter Range Parsing**: Supports flexible input formats ("3", "1-3", etc.)

### Database Design

#### Entity Relationship Model

```
┌───────────────────┐       ┌───────────────────┐       ┌───────────────────┐
│       Users       │       │    ReadingLogs    │       │   BookProgress    │
├───────────────────┤       ├───────────────────┤       ├───────────────────┤
│ id (PK)           │◄──────┤ user_id (FK)      │       │ user_id (FK)      │
│ name              │       │ book_id           │       │ book_id           │
│ email             │       │ chapter           │       │ book_name         │
│ password          │       │ passage_text      │       │ total_chapters    │
│ email_verified_at │       │ date_read         │       │ chapters_read     │
│ remember_token    │       │ notes_text        │       │ completion_percent│
│ created_at        │       │ created_at        │       │ is_completed      │
│ updated_at        │       │ updated_at        │       │ last_updated      │
└───────────────────┘       └───────────────────┘       └───────────────────┘
```

#### Key Design Decisions

1. **Denormalized BookProgress Table**: Optimizes statistics calculations by avoiding complex joins
2. **JSON Chapter Storage**: `chapters_read` stored as JSON array for flexible chapter tracking
3. **Composite Indexing**: Indexes on `(user_id, date_read)` for efficient calendar queries
4. **Date Normalization**: All dates stored as DATE type to avoid timezone complications

### Performance Architecture

#### Caching Strategy

```php
// High-Impact Caching Targets
Cache::remember("user_dashboard_stats_{$userId}", 300, fn() => $this->getDashboardStatistics($user));
Cache::remember("user_current_streak_{$userId}", 900, fn() => $this->calculateCurrentStreak($user));
Cache::remember("user_longest_streak_{$userId}", 3600, fn() => $this->calculateLongestStreak($user));
Cache::remember("bible_books_{$locale}", 86400, fn() => $this->listBibleBooks(null, $locale));
Cache::remember("user_calendar_{$userId}_{$year}", 1800, fn() => $this->getCalendarData($user, $year));
```

#### Cache Invalidation Strategy
- **Reading Log Creation**: Invalidates user stats, streaks, and calendar caches
- **Book Progress Updates**: Invalidates book progress and dashboard caches
- **Locale Changes**: Invalidates Bible books cache for specific locale

## Components and Interfaces

### Core Components

#### 1. Authentication System (Laravel Fortify)
**Interface**: Standard Laravel authentication with custom views
- **Registration**: Email/password with validation
- **Login**: Session-based authentication
- **Password Reset**: Email-based reset flow (requires email service)
- **Session Management**: Secure logout and session handling

#### 2. Reading Log Management
**Interface**: HTMX-powered modal forms with dynamic validation

```php
// Controller Interface
class ReadingLogController extends Controller
{
    public function create(Request $request): View
    public function store(Request $request): View
    public function index(Request $request): View|Paginator
}

// Service Interface
class ReadingLogService
{
    public function logReading(User $user, array $data): ReadingLog
    public function getReadingHistory(User $user, ?int $limit, ?string $startDate, ?string $endDate): Collection
    public function calculateCurrentStreak(User $user): int
    public function calculateLongestStreak(User $user): int
}
```

#### 3. Dashboard Analytics
**Interface**: Real-time statistics with HTMX updates

```php
// Statistics Service Interface
class UserStatisticsService
{
    public function getDashboardStatistics(User $user): array
    public function getCalendarData(User $user, ?string $year): array
    public function getStreakStatistics(User $user): array
    public function getBookProgressSummary(User $user): array
}
```

#### 4. Bible Reference System
**Interface**: Comprehensive Bible book management

```php
// Bible Reference Service Interface
class BibleReferenceService
{
    public function getBibleBook(int|string $identifier, ?string $locale): ?array
    public function validateBibleReference(int $bookId, int $chapter): bool
    public function parseChapterInput(string $chapterInput): array
    public function listBibleBooks(?string $testament, ?string $locale): array
}
```

### UI Component Architecture

#### Responsive Navigation System

**Mobile Design (< 1024px)**:
- **Bottom Tab Navigation**: 3-tab layout (Dashboard, History, Profile)
- **Floating Action Button**: Primary "Log Reading" action
- **Touch-Optimized**: 44px minimum touch targets

**Desktop Design (≥ 1024px)**:
- **Sidebar Navigation**: Fixed 256px width with comprehensive menu
- **Header Action Button**: "Log Reading" in content header
- **Two-Column Layout**: 70% main content, 30% supporting information

#### HTMX Integration Patterns

**Modal Forms**:
```html
<!-- Trigger -->
<button hx-get="/logs/create" hx-target="#modal-container" hx-swap="innerHTML">
    Log Reading
</button>

<!-- Response -->
<div id="reading-log-modal" class="modal">
    <form hx-post="/logs" hx-target="#modal-container" hx-swap="outerHTML">
        <!-- Form fields -->
    </form>
</div>
```

**Dynamic Content Updates**:
```html
<!-- Dashboard Statistics -->
<div id="dashboard-stats" hx-get="/dashboard/stats" hx-trigger="reading-logged from:body">
    <!-- Statistics content -->
</div>

<!-- Calendar Visualization -->
<div id="calendar-display" hx-get="/calendar" hx-trigger="reading-logged from:body">
    <!-- Calendar heatmap -->
</div>
```

## Data Models

### Reading Log Model

```php
class ReadingLog extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'chapter', 'passage_text', 
        'date_read', 'notes_text'
    ];
    
    protected $casts = [
        'date_read' => 'date',
        'book_id' => 'integer',
        'chapter' => 'integer'
    ];
    
    // Relationships
    public function user(): BelongsTo
    
    // Scopes
    public function scopeRecentFirst($query)
    public function scopeDateRange($query, $startDate, $endDate = null)
    public function scopeForBook($query, $bookId)
}
```

### Book Progress Model

```php
class BookProgress extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'book_name', 'total_chapters',
        'chapters_read', 'completion_percent', 'is_completed'
    ];
    
    protected $casts = [
        'chapters_read' => 'array',
        'completion_percent' => 'decimal:2',
        'is_completed' => 'boolean',
        'book_id' => 'integer',
        'total_chapters' => 'integer'
    ];
    
    // Business Logic Methods
    public function addChapter(int $chapter): void
    public function updateCompletionStatus(): void
    public function hasReadChapter(int $chapter): bool
}
```

### Bible Configuration Structure

```php
// config/bible.php
return [
    'books' => [
        1 => ['id' => 1, 'chapters' => 50, 'testament' => 'old'],
        2 => ['id' => 2, 'chapters' => 40, 'testament' => 'old'],
        // ... all 66 books
    ],
    'testaments' => [
        'old' => ['range' => [1, 39]],
        'new' => ['range' => [40, 66]]
    ],
    'default_locale' => 'en',
    'supported_locales' => ['en', 'fr']
];
```

## Error Handling

### Validation Strategy

**Form Validation**:
- **Laravel Form Requests**: Centralized validation rules
- **Bible Reference Validation**: Service-level validation with detailed error messages
- **HTMX Error Handling**: Return form partials with error states

**Error Response Patterns**:
```php
// Validation Errors
catch (ValidationException $e) {
    return view('partials.reading-log-form', [
        'books' => $this->bibleReferenceService->listBibleBooks(),
        'errors' => new MessageBag($e->errors())
    ]);
}

// Business Logic Errors
catch (InvalidArgumentException $e) {
    return view('partials.reading-log-form', [
        'books' => $this->bibleReferenceService->listBibleBooks(),
        'errors' => new MessageBag(['chapter_input' => [$e->getMessage()]])
    ]);
}
```

### Database Error Handling

**Duplicate Entry Prevention**:
- **Unique Constraints**: Prevent duplicate reading logs for same chapter/date
- **Graceful Degradation**: User-friendly error messages for constraint violations
- **Transaction Safety**: Ensure data consistency during multi-table updates

## Testing Strategy

### Unit Testing Approach

**Service Layer Testing**:
```php
// ReadingLogService Tests
- testLogSingleChapter()
- testLogChapterRange()
- testCalculateCurrentStreak()
- testCalculateLongestStreak()
- testValidateBibleReference()

// UserStatisticsService Tests
- testGetDashboardStatistics()
- testGetCalendarData()
- testGetBookProgressSummary()

// BibleReferenceService Tests
- testValidateBookId()
- testParseChapterInput()
- testGetLocalizedBookName()
```

**Integration Testing**:
```php
// Controller Tests
- testCreateReadingLogForm()
- testStoreReadingLog()
- testReadingLogValidation()
- testHTMXResponses()

// Feature Tests
- testCompleteUserJourney()
- testStreakCalculationAccuracy()
- testBookProgressTracking()
```

### Performance Testing

**Load Testing Targets**:
- **Dashboard Load Time**: < 500ms
- **Calendar Rendering**: < 200ms
- **Reading Log Submission**: < 300ms
- **Cache Hit Rate**: > 90% for frequent operations

**Database Performance**:
- **Query Optimization**: Monitor N+1 queries
- **Index Effectiveness**: Verify composite index usage
- **Cache Efficiency**: Monitor cache hit/miss ratios

## Security Considerations

### Authentication Security

**Laravel Fortify Integration**:
- **Password Hashing**: Bcrypt with appropriate cost factor
- **Session Security**: Secure session configuration
- **CSRF Protection**: Token validation on all forms
- **Rate Limiting**: Login attempt throttling

### Data Protection

**Input Validation**:
- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **XSS Protection**: Blade template escaping
- **Mass Assignment Protection**: Fillable attributes on models
- **File Upload Security**: Not applicable (no file uploads in MVP)

### API Security (Future)

**Sanctum Integration Ready**:
- **Token-Based Authentication**: For future mobile apps
- **API Rate Limiting**: Prevent abuse
- **CORS Configuration**: Secure cross-origin requests

## Deployment Architecture

### Laravel Cloud Integration

**Infrastructure**:
- **Serverless PostgreSQL**: Auto-scaling database
- **Edge Network**: Global CDN for static assets
- **Automatic Deployments**: Git-based deployment pipeline
- **Environment Management**: Secure environment variable injection

**Monitoring and Logging**:
- **Built-in Monitoring**: Laravel Cloud monitoring dashboard
- **Error Tracking**: Automatic error logging and alerting
- **Performance Metrics**: Response time and throughput monitoring

### Email Service Integration

**Production Email Setup**:
- **Postmark Integration**: Transactional email delivery
- **Template Management**: Branded email templates
- **Delivery Monitoring**: Email delivery tracking and analytics

**Development Email Setup**:
- **Mailtrap Integration**: Email testing and debugging
- **Local Development**: File-based email driver for local testing

## Internationalization Design

### Multilingual Architecture

**Translation System**:
```php
// Language Files Structure
lang/
├── en/
│   ├── bible.php (Book names, testaments)
│   ├── ui.php (Interface elements)
│   └── messages.php (User messages)
└── fr/
    ├── bible.php (Noms des livres, testaments)
    ├── ui.php (Éléments d'interface)
    └── messages.php (Messages utilisateur)
```

**Locale Management**:
- **Dynamic Locale Switching**: Session-based locale storage
- **URL Localization**: Optional locale prefixes for SEO
- **Fallback Strategy**: English as default fallback locale
- **RTL Support**: Not required for French, but architecture supports future expansion

### Content Adaptation

**Text Expansion Handling**:
- **Responsive Typography**: Flexible font sizes and line heights
- **Layout Flexibility**: CSS Grid and Flexbox for content adaptation
- **Button Sizing**: Dynamic button widths for longer French text
- **Navigation Labels**: Abbreviated labels for mobile navigation

This design document provides a comprehensive blueprint for the Bible Reading Habit Builder, balancing current implementation status with future enhancement opportunities while maintaining clean architecture principles and optimal user experience.