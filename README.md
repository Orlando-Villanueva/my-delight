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
- **Database**: MySQL with denormalized BookProgress table for efficient tracking
- **Caching**: Redis
- **Bible Reference System**: Static configuration approach via config files
- **Internationalization**: Laravel's built-in localization system
- **Testing**: Comprehensive test suite covering critical components

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js and npm
- MySQL 8.0+
- Redis server

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

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure the environment variables**
   Open `.env` file and update:
   - Database connection details
   - Redis configuration
   - Application settings
   - Language settings

6. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build frontend assets**
   ```bash
   npm run dev
   ```

8. **Run the application**
   ```bash
   php artisan serve
   ```
   Access the application at http://localhost:8000

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
