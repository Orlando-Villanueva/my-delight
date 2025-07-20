# Delight - Project Structure

## Directory Organization

### Core Application Code
- **`app/`**: Application code
  - **`Actions/`**: Single-purpose action classes
  - **`Console/`**: Artisan commands
  - **`Contracts/`**: Interfaces and contracts
  - **`Http/`**: Controllers, middleware, and requests
    - **`Controllers/`**: Handle HTTP requests
    - **`Middleware/`**: Request/response filters
    - **`Requests/`**: Form validation classes
  - **`Mail/`**: Mail classes
  - **`Models/`**: Eloquent models
  - **`Notifications/`**: Notification classes
  - **`Providers/`**: Service providers
  - **`Services/`**: Business logic services
    - `ReadingLogService.php`: Handles reading log operations
    - `UserStatisticsService.php`: Calculates user statistics
    - `BookProgressService.php`: Manages book progress tracking
  - **`View/`**: View composers and components

### Configuration
- **`config/`**: Configuration files
  - `app.php`: Application configuration
  - `auth.php`: Authentication settings
  - `bible.php`: Bible reference configuration
  - `database.php`: Database connections
  - `filesystems.php`: File storage settings
  - `fortify.php`: Laravel Fortify configuration

### Resources
- **`resources/`**: Frontend assets and templates
  - **`css/`**: Stylesheets
  - **`js/`**: JavaScript files
  - **`views/`**: Blade templates
    - **`auth/`**: Authentication views
    - **`components/`**: Reusable UI components
    - **`layouts/`**: Page layouts
    - **`partials/`**: Partial views for HTMX responses

### Database
- **`database/`**: Database-related files
  - **`factories/`**: Model factories
  - **`migrations/`**: Database migrations
  - **`seeders/`**: Database seeders

### Routes
- **`routes/`**: Application routes
  - `web.php`: Web routes
  - `api.php`: API routes
  - `console.php`: Console routes
  - `channels.php`: Broadcasting channels

### Tests
- **`tests/`**: Test files
  - **`Feature/`**: Feature tests
  - **`Unit/`**: Unit tests

### Documentation
- **`docs/`**: Project documentation
  - `Technical Architecture.md`: Architecture overview
  - `Database Schema Documentation.md`: Database schema details
  - `HTMX Implementation Guide.md`: HTMX usage patterns
  - `Alpine.js Component Guide.md`: Alpine.js usage patterns

### UI Prototype
- **`ui-prototype/`**: Next.js UI prototype

## Architectural Patterns

### Service Layer Pattern
- Controllers should be thin and delegate business logic to services
- Services handle all business logic and data manipulation
- Models represent data structures and relationships

### HTMX + Alpine.js Pattern
- HTMX for server-driven UI updates
- Alpine.js for client-side interactivity
- Server returns HTML fragments for HTMX requests
- Minimize client-side state management

### File Naming Conventions
- **Controllers**: Singular, suffixed with `Controller` (e.g., `ReadingLogController`)
- **Models**: Singular, PascalCase (e.g., `ReadingLog`)
- **Services**: Singular, suffixed with `Service` (e.g., `ReadingLogService`)
- **Migrations**: Timestamp prefix with snake_case description (e.g., `2024_01_15_create_reading_logs_table`)
- **Blade Views**: Snake_case (e.g., `reading_log.blade.php`)
- **Partials**: Prefixed with underscore (e.g., `_reading_form.blade.php`)

### Database Conventions
- Table names: Plural, snake_case (e.g., `reading_logs`)
- Primary keys: `id`
- Foreign keys: Singular model name with `_id` suffix (e.g., `user_id`)
- Timestamps: `created_at`, `updated_at`
- Soft deletes: `deleted_at`