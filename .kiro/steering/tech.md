# Technology Stack

## Backend Framework
- **Laravel 12** (PHP 8.2+) - Main application framework
- **Laravel Fortify** - Authentication (registration, login, password reset)
- **Laravel Telescope** - Development debugging and monitoring
- **Pest** - Testing framework (preferred over PHPUnit)

## Frontend Stack
- **HTMX** - Server-driven interactivity without complex JavaScript
- **Alpine.js** - Minimal JavaScript for UI interactions
- **Tailwind CSS 4.0** - Utility-first CSS framework
- **Vite** - Build tool and asset bundling

## Database
- **SQLite** - Local development (simple, file-based)
- **PostgreSQL 17** - Production (Laravel Cloud Serverless Postgres)
- **Redis** - Caching layer (production only)

## Architecture Patterns
- **Service Layer Pattern** - Business logic separation from controllers (thin controllers)
- **Repository Pattern** - Optional data access abstraction
- **HTMX-first approach** - Server-rendered HTML fragments over JSON APIs
- **Zero-Duplication Component Pattern** - Reusable Blade partials and components
- **Event-based cache invalidation** - Performance optimization for user statistics

## Development Tools
- **Laravel Pint** - Code formatting
- **Laravel Sail** - Docker development environment
- **Composer** - PHP dependency management
- **npm** - JavaScript dependency management

## Common Commands

### Development Server
```bash
# Start all services (server, queue, vite) - PREFERRED METHOD
composer dev

# Individual services (if needed)
# NOTE: NEVER use 'php artisan serve' - this project uses Laravel Herd
php artisan queue:listen --tries=1
npm run dev
```

### IMPORTANT: Laravel Herd Development
- **NEVER use `php artisan serve`** - This project uses Laravel Herd for local development
- Laravel Herd provides native web server with .test domains
- Site is automatically available at: https://delight.test (or similar based on directory name)
- Use Laravel Herd's GUI or configured local domains instead

### Testing
```bash
# Run test suite
composer test
# or
php artisan test
```

### Database
```bash
# Run migrations
php artisan migrate

# Run migrations with seeders
php artisan migrate --seed

# Fresh migration (development)
php artisan migrate:fresh --seed
```

### Code Quality
```bash
# Format code (REQUIRED before finalizing changes)
./vendor/bin/pint --dirty

# Static analysis (if configured)
./vendor/bin/phpstan analyse

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Development utilities
php artisan tinker
php artisan route:list
php artisan config:show
```

### Asset Building
```bash
# Development
npm run dev

# Production build
npm run build
```

## Key Configuration Files
- `composer.json` - PHP dependencies and scripts
- `package.json` - JavaScript dependencies
- `vite.config.js` - Asset building configuration
- `config/bible.php` - Bible reference data (books, chapters)
- `config/streak_messages.php` - Streak milestone messages
- `phpunit.xml` - Testing configuration
- `bootstrap/app.php` - Laravel 12 application bootstrapping (middleware, routing)
- `bootstrap/providers.php` - Service provider registration

## Code Quality Standards

### Required Practices
- **Always use proper imports** - never fully qualified class names in code
- **Never use raw SQL methods** - always use Eloquent and query builder
- **Extract complex Alpine.js logic** - use separate `<script>` tags instead of inline x-data
- **Run Pint before commits** - `./vendor/bin/pint --dirty` is required
- **Follow Service Layer Pattern** - business logic in services, not controllers

### Forbidden Practices
- ❌ `selectRaw()`, `whereRaw()`, `havingRaw()` - use Eloquent methods
- ❌ `new \Illuminate\Support\Collection()` - use proper imports
- ❌ `php artisan serve` - use Laravel Herd instead
- ❌ Complex inline Alpine.js - extract to separate functions