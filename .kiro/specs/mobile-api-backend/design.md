# Design Document

## Overview

This design transforms the existing Delight web application backend into a minimal mobile API for MVP. The solution leverages Laravel Sanctum for API authentication and creates essential RESTful endpoints focused on the core habit loop: authentication, reading log creation, reading history, and streak tracking. The design reuses existing services to ensure data consistency between web and mobile clients.

## Architecture

### API Architecture Pattern
- **RESTful API Design**: Standard HTTP methods (GET, POST, PUT, DELETE) with resource-based URLs
- **Service Layer Reuse**: Existing services (ReadingLogService, UserStatisticsService, etc.) will be reused without modification
- **Controller Separation**: New API controllers separate from web controllers for clean separation of concerns
- **Middleware Stack**: Authentication, rate limiting, and CORS middleware for mobile client support

### Authentication Flow
```
Mobile App → API Login → Laravel Sanctum → JWT Token → Authenticated Requests
```

### Data Flow
```
Mobile Request → API Controller → Existing Service Layer → Database → Cached Response → JSON Response
```

## Components and Interfaces

### 1. Authentication System

#### Laravel Sanctum Integration
- **Installation**: Add Laravel Sanctum package and configure for API token authentication
- **Token Management**: Personal access tokens with configurable expiration
- **Security**: Rate limiting and token revocation capabilities

#### API Authentication Endpoints
```php
POST /api/auth/login
POST /api/auth/register  
POST /api/auth/logout
GET  /api/auth/user
```

### 2. API Controllers

#### AuthController
- Handles user authentication, registration, and token management
- Validates credentials using existing Laravel authentication
- Returns standardized JSON responses with tokens

#### ReadingLogController (API)
- Exposes reading log functionality through REST endpoints
- Reuses existing ReadingLogService for all business logic
- Handles pagination for reading history

#### StreakController (API)
- Provides current and longest streak data
- Leverages existing streak calculation logic with caching
- Returns minimal streak information for mobile display

#### BibleReferenceController
- Exposes Bible book and chapter reference data
- Supports language preferences (English/French)
- Provides validation data for mobile form inputs

### 3. API Resource Classes

#### UserResource
```php
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

#### ReadingLogResource
```php
class ReadingLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'chapter' => $this->chapter,
            'passage_text' => $this->passage_text,
            'date_read' => $this->date_read,
            'notes_text' => $this->notes_text,
            'created_at' => $this->created_at,
        ];
    }
}
```

#### StreakResource
```php
class StreakResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'current_streak' => $this->current_streak,
            'longest_streak' => $this->longest_streak,
            'last_reading_date' => $this->last_reading_date,
        ];
    }
}
```

### 4. Request Validation Classes

#### LoginRequest
```php
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }
}
```

#### StoreReadingLogRequest
```php
class StoreReadingLogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'book_id' => 'required|integer|min:1|max:66',
            'chapter' => 'required|integer|min:1',
            'date_read' => 'nullable|date',
        ];
    }
}
```

## Data Models

### API Response Format
All API responses follow a consistent structure:

#### Success Response
```json
{
    "success": true,
    "data": {
        // Resource data here
    },
    "message": "Operation completed successfully",
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0"
    }
}
```

#### Error Response
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "email": ["The email field is required."]
        }
    },
    "meta": {
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0"
    }
}
```

#### Paginated Response
```json
{
    "success": true,
    "data": [
        // Array of resources
    ],
    "meta": {
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 150,
            "last_page": 10,
            "has_more": true
        },
        "timestamp": "2024-01-15T10:30:00Z",
        "version": "1.0"
    }
}
```

### Database Schema
No database changes required - the API will use existing tables:
- `users` - User accounts
- `reading_logs` - Reading entries
- `personal_access_tokens` - Sanctum API tokens (created by migration)

## Error Handling

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request (validation errors)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Unprocessable Entity (validation failed)
- `429` - Too Many Requests (rate limited)
- `500` - Internal Server Error

### Error Response Handler
```php
class ApiExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($exception);
        }
        
        return parent::render($request, $exception);
    }
    
    private function handleApiException(Throwable $exception): JsonResponse
    {
        // Convert exceptions to standardized API error responses
    }
}
```

### Validation Error Handling
- Field-level validation errors with specific messages
- Localized error messages supporting English and French
- Consistent error codes for mobile app error handling

## Testing Strategy

### API Testing Approach
- **Feature Tests**: Test complete API workflows end-to-end
- **Unit Tests**: Test individual API controllers and resources
- **Authentication Tests**: Verify token-based authentication flows
- **Integration Tests**: Ensure API and web app data consistency

### Test Structure
```
tests/
├── Feature/
│   ├── Api/
│   │   ├── AuthenticationTest.php
│   │   ├── ReadingLogApiTest.php
│   │   ├── StreakApiTest.php
│   │   └── BibleReferenceApiTest.php
│   └── Integration/
│       └── WebApiConsistencyTest.php
└── Unit/
    ├── Http/
    │   ├── Controllers/
    │   │   └── Api/
    │   └── Resources/
    └── Services/ (existing tests)
```

### Test Data Management
- **Database Factories**: Reuse existing factories for consistent test data
- **API Test Traits**: Common authentication and assertion helpers
- **Seeded Test Data**: Use existing seeders for comprehensive testing

### Performance Testing
- **Load Testing**: API endpoints under concurrent mobile client load
- **Cache Testing**: Verify caching behavior maintains performance
- **Response Time Testing**: Ensure mobile-appropriate response times

## Security Considerations

### API Security Measures
- **Rate Limiting**: Prevent abuse with configurable rate limits per endpoint
- **CORS Configuration**: Proper cross-origin resource sharing for mobile apps
- **Input Validation**: Comprehensive validation on all API inputs
- **SQL Injection Prevention**: Leverage Laravel's ORM for safe database queries

### Authentication Security
- **Token Expiration**: Configurable token lifetimes with refresh capability
- **Token Revocation**: Ability to revoke tokens on logout or security events
- **Secure Token Storage**: Guidelines for mobile app token storage
- **Password Security**: Maintain existing password hashing and validation

### Data Privacy
- **User Data Filtering**: Only expose necessary user data in API responses
- **Audit Logging**: Log API access for security monitoring
- **Data Encryption**: Ensure sensitive data encryption in transit and at rest

## Performance Optimization

### Caching Strategy
- **Reuse Existing Cache**: Leverage current caching for streak calculations
- **Cache Headers**: Proper HTTP cache headers for mobile client caching
- **Cache Invalidation**: Maintain existing cache invalidation on new reading logs

### Database Optimization
- **Query Optimization**: Reuse existing optimized service layer queries
- **Pagination**: Implement efficient pagination for reading log history

### Response Optimization
- **Minimal Payloads**: Keep API responses small for mobile networks
- **Compression**: Enable gzip compression for API responses

## Deployment Considerations

### Environment Configuration
- **CORS Settings**: Configure CORS for mobile app domains
- **Rate Limiting**: Basic rate limiting to prevent abuse

### Mobile App Integration
- **Simple Documentation**: Clear endpoint documentation for mobile developers
- **Offline Support**: API designed to support mobile offline reading log storage