# Database Schema Documentation

## Overview

This document outlines the database schema for the Delight MVP, including entity relationships, migration history, and design rationale.

## Entity Relationship Diagram

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

## Table Definitions

### Users Table

**Purpose**: Store user authentication and profile information.

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Indexes**:
- Primary key on `id`
- Unique index on `email`

### ReadingLogs Table

**Purpose**: Store individual Bible reading entries with chapter-level granularity.

```sql
CREATE TABLE reading_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id TINYINT UNSIGNED NOT NULL,
    chapter SMALLINT UNSIGNED NOT NULL,
    passage_text VARCHAR(100) NOT NULL,
    date_read DATE NOT NULL,
    notes_text TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, date_read),
    INDEX idx_user_book_chapter (user_id, book_id, chapter),
    UNIQUE KEY unique_user_book_chapter_date (user_id, book_id, chapter, date_read)
);
```

**Field Details**:
- `book_id`: References Bible book (1-66, maps to config/bible.php with translations in lang/ files)
- `chapter`: Chapter number within the book
- `passage_text`: Formatted reference (e.g., "Genesis 1", "John 3")
- `date_read`: Date when the reading occurred
- `notes_text`: Optional user notes (max 500 characters)

**Indexes**:
- Primary key on `id`
- Foreign key on `user_id`
- Composite index on `(user_id, date_read)` for streak calculations
- Composite index on `(user_id, book_id, chapter)` for book progress
- Unique constraint on `(user_id, book_id, chapter, date_read)` to prevent duplicates

### BookProgress Table

**Purpose**: Denormalized table for efficient book completion tracking and statistics.

```sql
CREATE TABLE book_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id TINYINT UNSIGNED NOT NULL,
    book_name VARCHAR(50) NOT NULL,
    total_chapters TINYINT UNSIGNED NOT NULL,
    chapters_read JSON NOT NULL DEFAULT '[]',
    completion_percent DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_book (user_id, book_id),
    INDEX idx_user_completion (user_id, completion_percent),
    INDEX idx_user_completed (user_id, is_completed)
);
```

**Field Details**:
- `book_id`: References Bible book (1-66)
- `book_name`: Cached book name for display
- `total_chapters`: Total chapters in the book (from config)
- `chapters_read`: JSON array of chapter numbers read
- `completion_percent`: Calculated percentage (0.00-100.00)
- `is_completed`: Boolean flag for 100% completion

**Design Rationale**:
- **Performance Optimization**: Avoids scanning all reading_logs for statistics
- **Denormalization**: Trades storage for query performance
- **JSON Storage**: Flexible storage for chapter tracking
- **Automatic Updates**: Triggered by reading_log insertions

## Migration Files

### 1. Create Reading Logs Table

```php
<?php
// database/migrations/2024_01_15_000001_create_reading_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('book_id')->unsigned();
            $table->smallInteger('chapter')->unsigned();
            $table->string('passage_text', 100);
            $table->date('date_read');
            $table->text('notes_text')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'date_read'], 'idx_user_date');
            $table->index(['user_id', 'book_id', 'chapter'], 'idx_user_book_chapter');
            
            // Prevent duplicate readings on same date
            $table->unique(['user_id', 'book_id', 'chapter', 'date_read'], 'unique_user_book_chapter_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_logs');
    }
};
```

### 2. Create Book Progress Table

```php
<?php
// database/migrations/2024_01_15_000002_create_book_progress_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('book_id')->unsigned();
            $table->string('book_name', 50);
            $table->tinyInteger('total_chapters')->unsigned();
            $table->json('chapters_read')->default('[]');
            $table->decimal('completion_percent', 5, 2)->default(0.00);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
            
            // Indexes for performance
            $table->unique(['user_id', 'book_id'], 'unique_user_book');
            $table->index(['user_id', 'completion_percent'], 'idx_user_completion');
            $table->index(['user_id', 'is_completed'], 'idx_user_completed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_progress');
    }
};
```

## Data Relationships

### Primary Relationships

1. **Users → ReadingLogs**: One-to-Many
   - One user can have many reading logs
   - Foreign key: `reading_logs.user_id`
   - Cascade delete: When user is deleted, all reading logs are deleted

2. **Users → BookProgress**: One-to-Many
   - One user can have progress for many books
   - Foreign key: `book_progress.user_id`
   - Cascade delete: When user is deleted, all book progress is deleted

### Derived Relationships

3. **ReadingLogs → BookProgress**: Indirect
   - No direct foreign key relationship
   - BookProgress is updated when ReadingLogs are created/modified
   - Relationship maintained through application logic

## Indexing Strategy

### Performance-Critical Queries

1. **Streak Calculation**:
   ```sql
   SELECT DISTINCT date_read 
   FROM reading_logs 
   WHERE user_id = ? 
   ORDER BY date_read DESC;
   ```
   - **Index**: `idx_user_date (user_id, date_read)`

2. **Book Progress Lookup**:
   ```sql
   SELECT * 
   FROM book_progress 
   WHERE user_id = ? 
   ORDER BY book_id;
   ```
   - **Index**: `unique_user_book (user_id, book_id)`

3. **Reading History**:
   ```sql
   SELECT * 
   FROM reading_logs 
   WHERE user_id = ? 
   AND date_read BETWEEN ? AND ?;
   ```
   - **Index**: `idx_user_date (user_id, date_read)`

4. **Statistics Queries**:
   ```sql
   SELECT COUNT(*) as books_completed 
   FROM book_progress 
   WHERE user_id = ? AND is_completed = true;
   ```
   - **Index**: `idx_user_completed (user_id, is_completed)`

## Data Integrity Constraints

### Business Rules Enforced by Database

1. **Unique Reading Logs**: Prevent duplicate chapter readings on same date
   - Constraint: `unique_user_book_chapter_date`

2. **Valid Book References**: Book IDs must be 1-66
   - Enforced by application validation (references config/bible.php with translations)

3. **Valid Chapter Numbers**: Chapters must be valid for the book
   - Enforced by application validation

4. **Date Constraints**: Reading dates cannot be in the future
   - Enforced by application validation

### Referential Integrity

1. **User Deletion**: Cascade delete all related data
2. **Data Consistency**: BookProgress automatically updated via application events

## Performance Considerations

### Critical Query Optimization Targets (PR5 Assessment)

Based on performance analysis of the current implementation, these are the highest-impact optimization targets:

#### 1. Streak Calculation Optimization

**Current Implementation Issues:**
```php
// Inefficient: Loads all reading dates into PHP memory
$readingDates = $user->readingLogs()
    ->select('date_read')
    ->distinct()
    ->orderBy('date_read', 'desc')
    ->pluck('date_read');
```

**Optimization Target:**
- Convert to SQL window functions for database-level calculation
- Add composite index: `(user_id, date_read)` (already exists)
- Consider materialized view for frequently accessed streak data

#### 2. Dashboard Statistics Optimization

**Current Bottleneck:**
- `UserStatisticsService::getDashboardStatistics()` executes 5+ separate queries
- No caching of expensive calculations
- Book progress summary loads all user records

**Optimization Targets:**
- Implement `Cache::remember()` for dashboard statistics (5-15 min TTL)
- Batch related queries into single optimized query
- Add specific indexes for common statistics patterns

#### 3. Calendar Data Generation Optimization

**Current Issue:**
```php
// Inefficient: Creates 365 array entries in PHP
while ($currentDate->lte($endDate)) {
    $calendar[$dateString] = [...];
    $currentDate->addDay();
}
```

**Optimization Target:**
- Use database aggregation with date generation
- Cache calendar data per user/year (30 min TTL)
- Consider PostgreSQL generate_series() for date ranges

### Query Optimization

1. **Denormalized BookProgress**: Eliminates need to scan reading_logs for statistics
2. **Composite Indexes**: Optimized for common query patterns:
   - `idx_user_date (user_id, date_read)` - Streak calculations ✅
   - `idx_user_book_chapter (user_id, book_id, chapter)` - Book progress ✅
   - `idx_user_completion (user_id, completion_percent)` - Statistics ✅
   - `idx_user_completed (user_id, is_completed)` - Book completion queries ✅
3. **JSON Storage**: Efficient storage for variable-length chapter lists

### Caching Strategy Integration

**High-Impact Cache Targets:**
```php
// Cache expensive dashboard statistics
Cache::remember("user_stats_{$userId}", 300, $statisticsCallback);

// Cache streak calculations (infrequent changes)
Cache::remember("user_streak_{$userId}", 900, $streakCallback);

// Cache calendar data (daily granularity)
Cache::remember("user_calendar_{$userId}_{$year}", 1800, $calendarCallback);
```

**Cache Invalidation Triggers:**
- Clear user caches on reading log creation/deletion
- Clear calendar cache on any reading date change
- Time-based fallback with appropriate TTL values

### Scaling Strategies

1. **Partitioning**: reading_logs table can be partitioned by user_id or date_read
2. **Archiving**: Old reading_logs can be archived while maintaining book_progress
3. **Caching**: Database and application-level caching for frequent queries
4. **Query Monitoring**: Use Laravel Telescope for production query analysis
5. **Index Optimization**: Add indexes based on actual query patterns in production

## Data Migration Strategy

### Initial Data Setup

1. **Bible Reference Data**: 
   - Configuration: `config/bible.php` (book structure, chapter counts)
   - Translations: `lang/{locale}/bible.php` (localized book names)
2. **User Seeding**: Factory-generated test users for development
3. **Sample Data**: Realistic reading logs for testing

### Future Migrations

1. **Additional Fields**: New columns can be added with default values
2. **Index Optimization**: New indexes can be added based on query patterns
3. **Data Cleanup**: Periodic cleanup of orphaned or invalid data

This schema design prioritizes query performance for the MVP's core features while maintaining data integrity and providing a foundation for future enhancements. 