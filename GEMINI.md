# Gemini Project Context: Delight Bible Habit Tracker

This document provides essential context for the "Delight" project, a web application designed to help users build and maintain a consistent Bible reading habit.

## Project Overview

*   **Purpose:** A beautiful and user-friendly Bible reading habit tracker. Key features include daily reading logs, streak tracking, a visual book completion grid, and user statistics.
*   **Backend:** Laravel (PHP) framework, following a Service Layer architectural pattern to ensure a clean separation of concerns.
*   **Frontend:** A modern, server-driven frontend using HTMX and Alpine.js. Styling is handled exclusively with Tailwind CSS. Asset bundling is managed by Vite.
*   **Database:** The application uses SQLite for local development for simplicity and PostgreSQL in the production environment.
*   **Testing:** The project has a comprehensive test suite using Pest for both unit and feature tests.

## Building and Running

### Initial Setup

1.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
2.  **Install JS Dependencies:**
    ```bash
    npm install
    ```
3.  **Create Environment File:**
    ```bash
    cp .env.example .env
    ```
4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```
5.  **Set up Database:** Create a `database/database.sqlite` file and run migrations.
    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```

### Development

*   **Start Vite Dev Server:**
    ```bash
    npm run dev
    ```
*   **Start Laravel Server:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://localhost:8000`. Alternatively, Laravel Herd can be used to serve the site (e.g., at `http://biblehabit.test`).

### Testing

*   **Run Test Suite:** Execute the full Pest test suite.
    ```bash
    php artisan test
    ```

### Code Style

*   **Format Code:** The project uses `laravel/pint` for code style.
    ```bash
    ./vendor/bin/pint
    ```

## Development Conventions

*   **Documentation First:** Before implementing any new feature, review the project documentation in the `/docs` directory. Do not implement features outside the documented scope without clarification.
*   **Service Layer:** Business logic should be encapsulated in service classes within the `app/Services` directory.
*   **Styling:** All styling MUST be done using Tailwind CSS utility classes. Do not write custom CSS.
*   **Interactivity:** Use HTMX and Alpine.js for frontend interactivity, preferring declarative HTMX attributes over custom JavaScript. Responses to HTMX requests should be HTML fragments, not JSON.
*   **Commits:** Follow conventional commit standards.
