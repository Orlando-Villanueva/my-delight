# Delight - Technical Stack

## Core Technologies
- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: HTMX + Alpine.js for server-driven interactivity
- **CSS Framework**: Tailwind CSS 4.0
- **Database**: 
  - **Local Development**: SQLite
  - **Production**: PostgreSQL 17 (Laravel Forge)
- **Caching**: Redis (in production)
- **Authentication**: Laravel Fortify
- **Testing**: Pest PHP (PHPUnit wrapper)

## Architecture Pattern
- **Service Layer Pattern**: Clean separation of concerns with services handling business logic
- **Server-Driven UI**: HTMX for server-side rendering with minimal JavaScript
- **Denormalized Data**: BookProgress table for efficient tracking and statistics

## Common Commands

### Setup Commands
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Set up SQLite database
touch database/database.sqlite

# Environment setup
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run dev
```

### Development Commands
```bash
# Start development server
php artisan serve

# Run development environment (server, queue, vite)
composer dev

# Run tests
composer test
# or
php artisan test

# Code style fixing
./vendor/bin/pint

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Production Commands
```bash
# Build frontend assets for production
npm run build

# Run migrations in production
php artisan migrate --force

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Forge deployment commands
php artisan down
git pull origin main
composer install --no-interaction --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## Environment Configuration
- **Local Development**: SQLite database with Laravel Herd
- **Production**: Laravel Forge with PostgreSQL and Redis
- **Environment Files**: 
  - `.env` - Local development
  - `.env.testing` - Testing environment
  - `.env.example` - Template for new environments

## Performance Considerations
- Use Redis caching for frequently accessed data
- Leverage denormalized BookProgress table for efficient statistics
- Implement proper indexing for common query patterns
- Cache dashboard statistics and streak calculations