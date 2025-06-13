# Application Architecture Diagram

## Overview

This document provides visual representations of the Bible Reading Habit Builder's system architecture, component relationships, and data flow patterns for the planned MVP implementation.

## System Architecture Overview

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
│  │  Controllers  │    │    Services   │    │  Middleware   │        │
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

## Component Relationships

### Frontend Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        Frontend Components                          │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │   HTMX Layer    │    │  Alpine.js      │    │   Tailwind CSS  │   │
│  │                 │    │  Components     │    │   Styling       │   │
│  │ • Server Comms  │    │                 │    │                 │   │
│  │ • HTML Fragments│    │ • Local State   │    │ • Responsive    │   │
│  │ • Form Handling │    │ • UI Interactions│   │ • Design System │   │
│  │ • Event Triggers│    │ • Validation    │    │ • Components    │   │
│  └─────────┬───────┘    └─────────┬───────┘    └─────────────────┘   │
│            │                      │                                  │
│            └──────────┬───────────┘                                  │
│                       │                                              │
└───────────────────────┼──────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      Laravel Backend                                │
└─────────────────────────────────────────────────────────────────────┘
```

### Backend Service Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        Laravel Application                          │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │   Controllers   │    │    Services     │    │   Repositories  │   │
│  │                 │    │                 │    │                 │   │
│  │ • DashboardCtrl │    │ • StreakService │    │ • ReadingLogRepo│   │
│  │ • ReadingLogCtrl│    │ • BibleService  │    │ • BookProgRepo  │   │
│  │ • StatisticsCtrl│    │ • ProgressSvc   │    │ • UserRepo      │   │
│  │ • AuthCtrl      │    │ • CacheService  │    │                 │   │
│  └─────────┬───────┘    └─────────┬───────┘    └─────────┬───────┘   │
│            │                      │                      │           │
│            └──────────┬───────────┼──────────────────────┘           │
│                       │           │                                  │
└───────────────────────┼───────────┼──────────────────────────────────┘
                        │           │
                        ▼           ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         Data Layer                                  │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │   Eloquent      │    │   Database      │    │     Cache       │   │
│  │   Models        │    │                 │    │                 │   │
│  │                 │    │ • Users         │    │ • Redis         │   │
│  │ • User          │    │ • ReadingLogs   │    │ • Statistics    │   │
│  │ • ReadingLog    │    │ • BookProgress  │    │ • Streak Data   │   │
│  │ • BookProgress  │    │                 │    │ • Session Data  │   │
│  └─────────────────┘    └─────────────────┘    └─────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

## Data Flow Diagrams

### Reading Log Creation Flow

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   User      │    │   HTMX      │    │ Laravel     │    │  Database   │
│   Browser   │    │   Request   │    │ Controller  │    │             │
└──────┬──────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘
       │                  │                  │                  │
       │ 1. Submit Form   │                  │                  │
       ├─────────────────►│                  │                  │
       │                  │ 2. POST /logs    │                  │
       │                  ├─────────────────►│                  │
       │                  │                  │ 3. Validate Data │
       │                  │                  ├─────────────────►│
       │                  │                  │                  │
       │                  │                  │ 4. Create Log    │
       │                  │                  ├─────────────────►│
       │                  │                  │                  │
       │                  │                  │ 5. Update Progress│
       │                  │                  ├─────────────────►│
       │                  │                  │                  │
       │                  │ 6. HTML Fragment │                  │
       │                  │◄─────────────────┤                  │
       │ 7. Update UI     │                  │                  │
       │◄─────────────────┤                  │                  │
       │                  │                  │                  │
       │ 8. Trigger Event │                  │                  │
       ├─────────────────►│                  │                  │
       │                  │ 9. GET /streak   │                  │
       │                  ├─────────────────►│                  │
       │                  │                  │ 10. Calc Streak │
       │                  │                  ├─────────────────►│
       │                  │ 11. Streak HTML  │                  │
       │                  │◄─────────────────┤                  │
       │ 12. Update Streak│                  │                  │
       │◄─────────────────┤                  │                  │
```

### Statistics Dashboard Flow

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   User      │    │   Alpine.js │    │   HTMX      │    │   Laravel   │
│   Browser   │    │   Component │    │   Requests  │    │   Backend   │
└──────┬──────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘
       │                  │                  │                  │
       │ 1. Load Dashboard│                  │                  │
       ├─────────────────►│                  │                  │
       │                  │ 2. Init Tabs     │                  │
       │                  ├─────────────────►│                  │
       │                  │                  │ 3. GET /stats    │
       │                  │                  ├─────────────────►│
       │                  │                  │                  │
       │                  │                  │ 4. Cache Check   │
       │                  │                  │ 5. Query DB      │
       │                  │                  │ 6. HTML Response │
       │                  │                  │◄─────────────────┤
       │                  │ 7. Update Content│                  │
       │                  │◄─────────────────┤                  │
       │ 8. Display Stats │                  │                  │
       │◄─────────────────┤                  │                  │
       │                  │                  │                  │
       │ 9. Click Tab     │                  │                  │
       ├─────────────────►│                  │                  │
       │                  │ 10. Switch View  │                  │
       │                  ├─────────────────►│                  │
       │                  │                  │ 11. GET /calendar│
       │                  │                  ├─────────────────►│
       │                  │                  │ 12. Calendar HTML│
       │                  │                  │◄─────────────────┤
       │                  │ 13. Update Panel │                  │
       │                  │◄─────────────────┤                  │
       │ 14. Show Calendar│                  │                  │
       │◄─────────────────┤                  │                  │
```

## Integration Patterns

### HTMX + Alpine.js Integration

```
┌─────────────────────────────────────────────────────────────────────┐
│                    Frontend Integration Layer                       │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                      HTMX Responsibilities                      │ │
│  │                                                                 │ │
│  │ • Server Communication                                          │ │
│  │ • Form Submissions                                              │ │
│  │ • HTML Fragment Updates                                         │ │
│  │ • Event Triggering                                              │ │
│  │ • Loading States                                                │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                    │                                 │
│                                    ▼                                 │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                    Alpine.js Responsibilities                   │ │
│  │                                                                 │ │
│  │ • Local State Management                                        │ │
│  │ • UI Interactions (dropdowns, modals)                          │ │
│  │ • Client-side Validation                                        │ │
│  │ • Reactive Data Binding                                         │ │
│  │ • Event Handling                                                │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### Caching Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                         Caching Strategy                            │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │  Application    │    │    Database     │    │     Redis       │   │
│  │     Cache       │    │     Queries     │    │     Cache       │   │
│  │                 │    │                 │    │                 │   │
│  │ • Route Cache   │    │ • Query Cache   │    │ • Session Data  │   │
│  │ • View Cache    │    │ • Result Cache  │    │ • User Stats    │   │
│  │ • Config Cache  │    │                 │    │ • Streak Data   │   │
│  └─────────────────┘    └─────────────────┘    └─────────────────┘   │
│                                                                     │
│  Cache Invalidation Strategy:                                       │
│  • Time-based TTL (5-15 min for stats, 24h+ for static data)       │
│  • Event-based (new reading logs invalidate user stats)            │
│  • Tag-based selective invalidation                                │
└─────────────────────────────────────────────────────────────────────┘
```

## Security Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                        Security Layers                              │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                    Authentication Layer                         │ │
│  │                                                                 │ │
│  │ • Laravel Sanctum (Cookie + Token Auth)                        │ │
│  │ • Session Management                                            │ │
│  │ • Password Hashing (bcrypt)                                     │ │
│  │ • Remember Me Tokens                                            │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                    │                                 │
│                                    ▼                                 │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                    Authorization Layer                          │ │
│  │                                                                 │ │
│  │ • Route Middleware                                              │ │
│  │ • User-based Data Isolation                                     │ │
│  │ • CSRF Protection                                               │ │
│  │ • Rate Limiting                                                 │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                                    │                                 │
│                                    ▼                                 │
│  ┌─────────────────────────────────────────────────────────────────┐ │
│  │                    Data Protection Layer                        │ │
│  │                                                                 │ │
│  │ • Input Validation                                              │ │
│  │ • SQL Injection Prevention (Eloquent ORM)                      │ │
│  │ • XSS Protection                                                │ │
│  │ • HTTPS Enforcement                                             │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────┘
```

## Deployment Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                      Production Environment                         │
│                        (Laravel Cloud)                              │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │   Web Server    │    │   Application   │    │    Database     │   │
│  │                 │    │     Server      │    │                 │   │
│  │ • Load Balancer │    │                 │    │ • PostgreSQL    │   │
│  │ • SSL/TLS       │    │ • Laravel App   │    │ • Automated     │   │
│  │ • Static Assets │    │ • Queue Workers │    │   Backups       │   │
│  │ • CDN           │    │ • Cron Jobs     │    │ • Monitoring    │   │
│  └─────────────────┘    └─────────────────┘    └─────────────────┘   │
│                                                                     │
│  ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐   │
│  │     Redis       │    │   Monitoring    │    │     Logging     │   │
│  │                 │    │                 │    │                 │   │
│  │ • Session Store │    │ • Health Checks │    │ • Error Tracking│   │
│  │ • Cache Store   │    │ • Performance   │    │ • Audit Logs    │   │
│  │ • Queue Backend │    │ • Alerts        │    │ • Debug Logs    │   │
│  └─────────────────┘    └─────────────────┘    └─────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

This architecture provides a scalable, maintainable foundation for the Bible Reading Habit Builder MVP while supporting future growth and feature expansion. 