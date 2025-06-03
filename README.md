# Bible Reading Habit Builder

A web application designed to help users build and maintain a consistent Bible reading habit through tracking, streaks, and visual progress indicators.

## Features

### MVP (Phase 1) Features
- **Daily Reading Log**: Track your daily Bible reading with a structured book/chapter selector
- **Streak Tracking**: Maintain reading streaks with a 1-day grace period
- **Book Completion Grid**: Visual representation of reading progress across all Bible books
- **Basic Statistics**: 
  - Current streak counter
  - All-time longest streak
  - Total chapters read
  - Books started vs. completed count
  - Calendar view of reading activity
- **Multilingual Support**: English and French language options
- **Caching**: Redis-based caching for frequently accessed data

### Post-MVP (Phase 2) Features
- Advanced statistics with weekly analysis
- Reading pattern insights
- Expanded visualizations
- Additional language support

## Technical Overview

- **Framework**: Laravel (PHP)
- **Database**: 
  - **Local Development**: SQLite for simplicity and ease of setup
  - **Production**: Laravel Cloud's Serverless Postgres (PostgreSQL 17) with denormalized BookProgress table for efficient tracking
- **Caching**: Redis (in production)
- **Bible Reference System**: Static configuration approach via config files
- **Internationalization**: Laravel's built-in localization system
- **Testing**: Comprehensive test suite covering critical components

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js and npm
- SQLite (for local development)
- [Optional] Redis server (for production caching)

### Installation

1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd biblehabit
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Set up SQLite database**
   ```bash
   # Create SQLite database file
   touch database/database.sqlite
   
   # Update .env for SQLite
   # DB_CONNECTION=sqlite
   # DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
   ```
   
   On Windows, use the full path in .env:
   ```
   DB_DATABASE=W:/Projects/Herd/biblehabit/database/database.sqlite
   ```

5. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run dev
   ```

8. **Access the application**
   ```bash
   php artisan serve
   ```
   - Access at http://localhost:8000
   - Or use Laravel Herd: open http://biblehabit.test (if configured)

## Production Setup (Laravel Cloud)

1. **Database**: Laravel Cloud automatically provisions a Serverless Postgres database (PostgreSQL 17)
2. **Environment Variables**: Database credentials are automatically injected
3. **Migrations**: Run migrations via Laravel Cloud's CLI or deployment pipeline
   ```bash
   php artisan migrate --force
   ```

## Local Development Tips

- **SQLite**: Default for local development (fast, file-based, no server required)
- **PostgreSQL**: Optional for local development (matches production environment)
  - Install PostgreSQL and update `.env` with PostgreSQL credentials
  - Run migrations after switching database types
- **Environment Files**: Keep sensitive credentials in `.env` (never commit this file)
- **Debugging**: Set `APP_DEBUG=true` in development for detailed error messages

### Additional Configuration

- **Redis Setup**: Ensure Redis server is running for caching functionality
- **Language Settings**: Default language can be configured in `config/app.php`
- **Bible Reference Data**: Configuration files are stored in `config/bible.php`

## Testing

Run the test suite with:
```bash
php artisan test
```

## License

This project is licensed under the MIT License.
