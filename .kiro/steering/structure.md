# Project Structure

## Root Directory
```
delight/
├── app/                    # Laravel application code
├── bootstrap/              # Application bootstrapping
├── config/                 # Configuration files
├── database/               # Database migrations, factories, seeders
├── docs/                   # Project documentation
├── lang/                   # Internationalization files
├── public/                 # Web server document root
├── resources/              # Views, CSS, JS, and other assets
├── routes/                 # Route definitions
├── storage/                # File storage and logs
├── tests/                  # Test files
├── .kiro/                  # Kiro IDE configuration
├── composer.json           # PHP dependencies
├── package.json            # JavaScript dependencies
├── vite.config.js          # Asset build configuration
└── README.md               # Project documentation
```

## Application Structure (app/)

### Core Application
```
app/
├── Http/
│   ├── Controllers/        # HTTP request handlers
│   └── Middleware/         # HTTP middleware
├── Models/                 # Eloquent models
│   ├── User.php           # User authentication model
│   ├── ReadingLog.php     # Bible reading entries
│   └── BookProgress.php   # Book completion tracking
├── Services/              # Business logic layer
│   ├── ReadingLogService.php      # Reading log operations
│   ├── UserStatisticsService.php  # Dashboard statistics
│   ├── BibleReferenceService.php  # Bible data operations
│   ├── BookProgressService.php    # Book progress tracking
│   └── StreakStateService.php     # Streak calculations
├── Providers/             # Service providers
├── Actions/               # Laravel Fortify actions
├── Mail/                  # Email templates and logic
├── Notifications/         # User notifications
└── View/                  # View components
```

### Key Services Architecture
- **ReadingLogService**: Handles Bible reading log creation, validation, and retrieval
- **UserStatisticsService**: Calculates dashboard metrics and progress statistics
- **BibleReferenceService**: Manages Bible book data and validation
- **BookProgressService**: Tracks completion status for all 66 Bible books
- **StreakStateService**: Calculates current and longest reading streaks

## Frontend Structure (resources/)

### Views (Blade Templates)
```
resources/views/
├── layouts/               # Base layouts
├── components/            # Reusable UI components
├── auth/                  # Authentication pages
├── logs/                  # Reading log pages
├── partials/              # Partial templates
├── dashboard.blade.php    # Main dashboard
└── landing.blade.php      # Landing page
```

### Assets
```
resources/
├── css/
│   └── app.css           # Main Tailwind CSS file
└── js/
    ├── app.js            # Main JavaScript entry
    └── bootstrap.js      # JavaScript bootstrapping
```

## Database Structure

### Migrations
```
database/migrations/
├── create_users_table.php
├── create_reading_logs_table.php
├── create_book_progress_table.php
└── [timestamp]_*.php     # Additional migrations
```

### Core Models
- **User**: Authentication and user data
- **ReadingLog**: Individual Bible reading entries (book_id, chapter, date_read, notes)
- **BookProgress**: Denormalized progress tracking for each Bible book per user

### Factories & Seeders
```
database/
├── factories/            # Model factories for testing
└── seeders/             # Database seeding
```

## Configuration Files

### Key Configurations
- `config/bible.php` - Bible reference data (66 books, chapter counts)
- `config/fortify.php` - Authentication configuration
- `config/telescope.php` - Development debugging
- `config/streak_messages.php` - Streak milestone messages

## Internationalization
```
lang/
├── en/                   # English translations
│   └── bible.php        # Bible book names in English
└── fr/                   # French translations
    └── bible.php        # Bible book names in French
```

## Testing Structure
```
tests/
├── Feature/              # Integration tests
├── Unit/                 # Unit tests
├── Pest.php             # Pest configuration
└── TestCase.php         # Base test case
```

## Development Files
- `.kiro/` - Kiro IDE configuration and specs
- `docs/` - Comprehensive project documentation
- `phpunit.xml` - Testing configuration
- `.env.example` - Environment configuration template

## Key Architectural Patterns

### Service Layer Pattern
Business logic is encapsulated in service classes rather than controllers, promoting:
- Clean separation of concerns
- Testable business logic
- Reusable code across different contexts
- Thin controllers that delegate to services

### HTMX-First Frontend
- Server-rendered HTML fragments (not JSON APIs)
- Controllers support both HTMX and standard Laravel requests
- Uses `hx-*` attributes for interactions
- Progressive enhancement approach
- Zero-Duplication Component Pattern with reusable Blade partials

### Bible Reference System
- Static configuration approach via `config/bible.php`
- 66 books with chapter counts and testament organization
- Translations in `lang/{locale}/bible.php` files
- BibleReferenceService handles validation and formatting
- Supports English and French localization

### Performance Optimizations
- **Denormalized Progress Tracking**: `BookProgress` table for efficient statistics
- **Caching Strategy**: Dashboard stats (5min), streaks (15min), Bible data (24hr)
- **Event-based cache invalidation**: Clears relevant caches on new reading logs
- **Composite database indexes**: Optimized for calendar and streak queries

### Authentication & Security
- Laravel Fortify for authentication (not complex HTMX auth)
- Standard Laravel forms with proper CSRF protection
- Custom middleware for security headers and query logging