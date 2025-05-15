# **Bible Reading Habit Builder \- Development Roadmap**

**Core Tech Stack:**

* **Backend:** Laravel  
* **Database:** Co-located Managed Database (e.g., PostgreSQL via PaaS)  
* **Web Frontend:** HTMX \+ Alpine.js (served by Laravel)  
* **Deployment (Web App & Backend):** Railway.app (PaaS)

## **Phase 1: MVP Web App \- Foundation & Core Loop**

* **Goal:** Launch the core "Read \-\> Log \-\> See Progress (Streak/History)" loop with essential features as defined in the "Bible App MVP" document. Validate the concept quickly.  
* **Estimated Time:** 6-9 Weeks  
* **Tasks:**  
  1. **Project Setup & Environment (3-5 days)**  
     * Set up a new Laravel project.  
     * Configure local development environment (e.g., Laravel Herd, Laragon, Docker with Sail).  
     * Initialize Git repository (e.g., on GitHub, GitLab).  
     * Set up Railway.app and provision a managed PostgreSQL database.  
     * Configure basic deployment pipeline (e.g., Git push to deploy to a staging/development environment on the PaaS).  
  2. **Database Schema & Migrations \- MVP (3-4 days)**  
     * Design database schema for core tables:
       * users (id, name, email, password, etc.)
       * reading\_logs (id, user\_id, date\_read, passage\_text, notes\_text)
       * book\_progress (id, user\_id, book\_id, book\_name, total\_chapters, chapters\_read, completion\_percent, is\_completed, last\_updated)
     * Create Laravel migrations for these tables
     * Define Eloquent models for User, ReadingLog, and BookProgress
     * Create Bible reference configuration file (config/bible.php) with static data for all 66 books
  3. **User Authentication (Web) (3-5 days)**  
     * Implement custom email/password registration and login compatible with HTMX + Alpine.js frontend.  
     * Create basic views for login, registration, and password reset that integrate with HTMX approach.  
     * Ensure authenticated users have a dedicated dashboard/home area.  
     * Note: This approach avoids Laravel starter kits as they're not compatible with our HTMX architecture.  
  4. **Daily Reading Log Input (Web) (5-7 days)**  
     * Create a form for users to log their daily reading with a two-step structured selector:  
       * Step 1: Book Selection - Dropdown of all 66 Bible books  
       * Step 2: Chapter Selection - Dynamic dropdown of valid chapters for selected book  
       * Date selector (defaults to today)  
       * Small plain text area for basic notes  
     * Develop Laravel controller logic to:  
       * Handle form submission and validate input  
       * Save ReadingLog entries to the database (associated with the authenticated user)  
       * Update BookProgress table with newly read chapters  
     * Implement a BibleReferenceService to access static book/chapter data from config  
     * Use HTMX for form submission to provide a smooth UX without full page reloads
  5. **Streak Calculation & Display (Web) (4-6 days)**  
     * Develop a StreakService to calculate current and longest streaks:  
       * Implement 1-day grace period (streak continues if user reads either today OR yesterday)  
       * Normalize dates to start of day to avoid timezone issues  
       * Handle edge cases like non-sequential data entry  
     * Create models and controllers to display streak information  
     * Display current streak prominently on the dashboard with visual indicator (e.g., flame icon)  
     * Show all-time longest streak for additional motivation  
     * Use HTMX to refresh this display after a new log is entered
  6. **History Visualization (Basic Calendar) (Web) (4-6 days)**  
     * Develop backend logic to fetch all ReadingLog dates for the authenticated user  
     * Create a simple calendar view (e.g., using HTML table or CSS Grid, styled to resemble GitHub's contribution graph) where days with logged readings are visually distinct  
     * Implement hover/click functionality to see what was read on a specific day  
     * Display this calendar on the user's dashboard  
     * Use HTMX to refresh the calendar when new readings are logged

  7. **Book Completion Tracking & Statistics (Web) (5-7 days)**  
     * Implement the BookProgress denormalized table with incremental updates:  
       * Create controller logic that updates BookProgress whenever a ReadingLog is created  
       * Calculate completion percentage based on chapters read vs. total chapters  
       * Track which books are started vs. completed  
     * Develop a Book Completion Grid UI showing all 66 books with status indicators:  
       * Not started: Light gray  
       * In progress: Blue  
       * Completed: Green  
     * Create a simple statistics dashboard showing:  
       * Current streak and all-time longest streak  
       * Total chapters read  
       * Books started vs. completed count  
       * Total days with reading activity  
     * Use HTMX for dynamic updates of statistics components
  8. **UI Implementation & Brand Identity (6-8 days)**  
      * Implement the defined color palette, typography system, and iconography from the UI Requirements Document
      * Set up Tailwind CSS with custom configuration for the brand colors and typography
      * Integrate Phosphor Icons library for consistent iconography across the application
      * Create a clean, intuitive UI prioritizing ease of use and clarity
      * Implement responsive layouts:
        * Mobile: Bottom tab bar navigation with 4 primary destinations
        * Desktop: Left sidebar navigation with expandable sections
      * Design clear visualizations for streaks, history, and statistics
      * Create card-based components for discrete information chunks
      * Develop the Book Completion Grid with proper color-coding (not started: light gray, in progress: blue, completed: green)
      * Ensure all components are responsive across defined breakpoints (mobile, tablet, desktop)

  9. **French Language Support (3-4 days)**  
     * Implement Laravel's localization system  
     * Create translation files for all UI elements and messages  
     * Extend Bible reference configuration to support French book names  
     * Add language toggle to user interface  
     * Implement locale-aware date formatting  
     * Test with native French speakers from Quebec  
  10. **Caching Implementation (2-3 days)**  
     * Set up Redis for caching using Railway's native Redis support  
     * Implement caching for frequently accessed data:  
       * User statistics (current streak, longest streak)  
       * Book completion data  
       * Reading history calendar  
     * Configure cache invalidation on new reading logs  
     * Implement fallback mechanisms if cache is unavailable  
     * Add cache monitoring and debugging tools  

  11. **Accessibility Implementation (3-4 days)**
      * Implement WCAG 2.1 AA standards from the beginning
      * Ensure proper color contrast ratios (minimum 4.5:1 for normal text)
      * Add focus indicators for keyboard navigation
      * Implement proper ARIA labels for interactive elements
      * Ensure screen reader compatibility
      * Test with keyboard-only navigation
      * Verify support for text resizing up to 200%
      * Ensure touch targets meet minimum size requirements (44px × 44px)

  12. **Performance Optimization (2-3 days)**
      * Optimize for core web vitals metrics specified in UI Requirements:
        * First Contentful Paint (FCP) under 1.5 seconds
        * Time to Interactive (TTI) under 3.5 seconds
      * Implement image optimization and lazy loading
      * Set up appropriate loading indicators for HTMX requests
      * Minimize CSS and JavaScript bundle sizes
      * Implement efficient HTMX swap strategies to reduce DOM manipulation

  13. **HTMX & Alpine.js Integration (3-4 days)**
      * Implement HTMX patterns for dynamic content updates:
        * Use `hx-get` and `hx-post` for server communication
        * Implement `hx-swap` for smooth transitions
        * Use `hx-target` to update specific page sections
        * Add appropriate loading indicators
      * Implement Alpine.js for interactive components:
        * Form validation states
        * Dropdowns and toggles
        * Responsive menu behavior
        * Simple animations for streak updates
      * Ensure proper integration between HTMX and Alpine.js components

  14. **Validation & Quality Assurance (4-5 days)**
      * Cross-browser testing (Chrome, Firefox, Safari, Edge)
      * Mobile device testing on iOS and Android
      * WCAG 2.1 AA compliance validation
      * Performance testing using Lighthouse
      * Usability testing with representative users
      * Write basic Laravel feature tests for critical paths (authentication, log creation, streak calculation)
      * Test BookProgress updates and statistics calculations
      * Verify cache performance and reliability
  15. **Deployment to Production & Basic Monitoring (1-2 days)**  
      * Deploy the MVP to the production environment on Railway.app  
      * Set up basic error tracking (e.g., Sentry free tier, Laravel's built-in logging)  
      * Configure initial database backup strategy
      * Set up performance monitoring for UI metrics

## **Phase 2: Web App \- Advanced Statistics & Core Enhancements**

* **Goal:** Expand on the MVP's basic statistics with more detailed analytics and add key habit-forming features.  
* **Estimated Time:** 6-9 Weeks  
* **Tasks:**  
  1. **Advanced Statistics Implementation (7-9 days)**  
     * Weekly Statistics Analysis:
       * Generate week-by-week reading summaries
       * Track number of chapters read per week
       * Calculate weekly reading consistency percentage
       * Identify strongest and weakest reading days
       * Compare current week to previous weeks
     * Advanced Visualizations:
       * Heat maps showing reading intensity
       * Progress charts with trend lines
       * Interactive filters for date ranges
     * Reading Patterns Analysis:
       * Day-of-week patterns identification
       * Time-of-day analysis (if timestamp data available)
       * Consistency metrics and suggestions

  2. **Reading Plans Integration (7-10 days)**  
     * Database: Add tables for reading\_plans (name, description, list of passages/days) and user\_reading\_plan\_progress (user\_id, plan\_id, current\_day, completed\_passages)  
     * Backend: Controllers and services for fetching plans, user subscription to plans, marking plan entries as complete  
     * Frontend: UI for plan discovery, viewing active plan and progress, marking plan days as complete  

  3. **Basic Goal Setting (5-7 days)**  
     * Database: Add tables for goals (type, target, timeframe) and goal\_progress (measurements)  
     * Backend: Logic for users to create simple goals (e.g., "Read X chapters daily")  
     * Frontend: UI for creating and viewing active goals with progress indicators

  4. **Enhanced Notes & Reflection (Basic Tagging) (4-6 days)**  
     * Database: Add tags table and a pivot table for reading\_log\_tag.  
     * Backend: Logic to allow users to add tags to their reading log notes. Basic search/filter of notes by tags.  
     * Frontend: UI to add/manage tags during note entry. UI to view notes filtered by tags.  

  5. **"Catch-Up" / Grace Mechanism (Streak Forgiveness \- Enhanced) (3-5 days)**  
     * Backend: Expand on the MVP's 1-day grace period with more flexible options:  
       * Allow logging for multiple previous days with a weekly limit  
       * Implement a "streak freeze" concept (earned through consistent reading)  
     * Frontend: Clear UI for these mechanisms with helpful explanations  

  6. **UX/UI Refinement for Web App (5-6 days)**  
      * Refined UI with more polished styles, transitions, and micro-interactions  
      * Improved flow based on user testing and feedback from MVP  
      * Enhance visualization components (statistics charts, book completion grid)  
      * Improve navigation and overall user experience  
      * Refine animations for streak increases and other achievement indicators
      * Enhance interactive calendar with detailed reading information on click/tap
      * Optimize mobile experience based on real-world usage patterns

  7. **Testing & Bug Fixing (Phase 2 Scope) (4-5 days)**  
     * Manual and automated testing for new features  
     * Performance testing for advanced statistics calculations  
     * Browser compatibility testing  
  8. **Documentation Update (1-2 days)**  
     * Update Technical Architecture document with Phase 2 implementations  
     * Align Product Requirements Document with newly added features  
     * Document API endpoints for future mobile development

## **Phase 3: Web App \- Engagement & UX Refinements**

* **Goal:** Deepen user engagement and improve the overall user experience with more motivating features.  
* **Estimated Time:** 6-9 Weeks  
* **Tasks:**  
  1. **Milestones & Achievements (5-8 days)**  
     * Database: achievements table (name, description, criteria), user\_achievements table.  
     * Backend: Logic to check for and award achievements (e.g., streak lengths, books completed, plans completed).  
     * Frontend: UI to display earned achievements to the user.  
  2. **Personalized Insights (Basic) (5-7 days)**  
     * Backend: Develop logic to calculate basic insights (e.g., "You read most on X day," "Average chapters per log"). This might involve more complex queries or simple data aggregation.  
     * Frontend: Display these insights on the user's dashboard.  
  3. **Visual Progress Beyond Calendar (e.g., Books Read) (4-6 days)**  
     * Backend: Logic to track and aggregate which books/chapters have been covered based on logs.  
     * Frontend: Simple visual representation (e.g., a list of Bible books, with completed ones highlighted or a progress bar per book).  
  4. **Gentle Reminders (Email) (4-6 days)**  
     * Backend: Set up Laravel's email capabilities. Implement a basic daily/weekly email reminder system (opt-in) using Laravel's Task Scheduling and Queues (for sending emails asynchronously).  
     * Frontend: UI for users to configure their reminder preferences.  
  5. **Improved Onboarding Experience (3-4 days)**  
     * Develop a simple guided tour or a series of introductory tips for new users explaining core features (streaks, logging, plans).  
  6. **Accessibility Enhancements (2-3 days)**  
      * Conduct comprehensive accessibility audit against WCAG 2.1 AA standards
      * Address any accessibility issues identified in real-world usage
      * Implement advanced accessibility features beyond minimum requirements
      * Test with assistive technology users for feedback
  7. **Advanced Notes Features (Optional Prompts) (3-5 days)**  
     * Backend: Store predefined reflection prompts.  
     * Frontend: Option for users to see and use these prompts within their notes section.  
  8. **Testing & Bug Fixing (Phase 3 Scope) (5-7 days)**

## **Phase 4: Web App \- API Foundation & Advanced Technical Features**

* **Goal:** Prepare the backend to support future mobile apps by building out a JSON API. Implement more advanced technical features.  
* **Estimated Time:** 6-10 Weeks  
* **Tasks:**  
  1. **Laravel Sanctum API Authentication (4-6 days)**  
     * Configure Laravel Sanctum for token-based API authentication.  
     * Implement API endpoints for user registration and login that return API tokens.  
  2. **Develop JSON API Endpoints for Core Features (10-15 days)**  
     * Create API routes (routes/api.php) and controller methods to expose core functionalities via JSON:  
       * Logging readings (POST /api/logs)  
       * Fetching reading logs (GET /api/logs)  
       * Fetching streak (GET /api/user/streak)  
       * Fetching calendar history data (GET /api/user/calendar)  
       * Fetching/managing reading plans & progress (GET, POST /api/plans/...)  
       * Fetching/managing goals (GET, POST /api/goals/...)  
     * Use Laravel API Resources to standardize JSON responses.  
     * Ensure API endpoints are protected by Sanctum authentication.  
  3. **API Documentation (Basic) (3-4 days)**  
     * Create basic API documentation (e.g., using tools like Scribe or just a well-structured Postman collection/Markdown file) for the developed endpoints.  
  4. **Data Backup & Export (User-facing) (4-6 days)**  
     * Backend: Develop functionality for users to export their reading history and notes (e.g., as CSV or JSON).  
     * Frontend: UI for users to request their data export.  
     * Ensure your PaaS has automated database backup solutions configured.  
  5. **Bible Text Integration (Future \- Research & Basic Setup) (5-8 days)**  
     * Research available Bible APIs (e.g., ESV API, American Bible Society, etc.) considering terms of use and API limits.  
     * If feasible, implement a basic integration to display Bible text for a logged passage or for readings in a plan. This might be read-only initially.  
     * This is a complex feature; for this phase, focus on the backend capability and perhaps a very simple frontend display.  
  6. **Refactor Core Logic into Services (if not already done) (Ongoing, 3-5 days)**  
     * Ensure business logic (streak calculation, plan progress, etc.) is in reusable service classes, so both web controllers (returning HTML) and API controllers (returning JSON) can use them.  
  7. **Comprehensive Testing (Web & API) (7-10 days)**  
     * Thorough testing of all web features and new API endpoints.  
     * Use tools like Postman or Insomnia for API testing.  
     * Expand automated test suite (feature and unit tests).

## **Phase 5: Mobile App Development \- iOS (MVP)**

* **Goal:** Develop a native iOS MVP that consumes the Laravel API and provides core tracking functionality. *This phase is significantly later and assumes the web app is mature and stable.*  
* **Estimated Time:** 10-16 Weeks (Highly dependent on mobile dev experience and chosen tools)  
* **Tasks:**  
  1. **Technology Choice & Setup (1-2 weeks)**  
     * Decide on iOS development approach (Native Swift, or cross-platform like React Native, Flutter). This roadmap assumes Swift for a true native experience.  
     * Set up iOS development environment (Xcode).  
  2. **Basic UI/UX Design for Mobile MVP (1-2 weeks)**  
     * Design core screens: Login, Registration, Dashboard (Streak, Log Input), Reading Log Form, History Calendar.  
  3. **API Integration Layer (1-2 weeks)**  
     * Implement networking layer in Swift to communicate with your Laravel JSON API (authentication, data fetching, posting data).  
  4. **User Authentication (Mobile) (1 week)**  
     * Implement login and registration screens that use the API. Securely store API tokens.  
  5. **Daily Reading Log Input (Mobile) (2-3 weeks)**  
     * Native UI for logging readings and notes, posting data to the API.  
  6. **Streak & History Display (Mobile) (2-3 weeks)**  
     * Fetch and display streak and calendar history from the API.  
  7. **Basic Offline Support (Consideration) (1-2 weeks)**  
     * Consider very basic offline viewing of already fetched data (e.g., streak, recent logs). Full offline sync is complex.  
  8. **Testing on Devices & Simulators (1-2 weeks)**  
  9. **App Store Submission Prep (1 week)**

## **Phase 6: Mobile App Development \- Android (MVP)**

* **Goal:** Develop a native Android MVP. *Can be parallel to iOS if resources allow and a cross-platform framework isn't used, or sequential.*  
* **Estimated Time:** 10-16 Weeks  
* **Tasks:** (Similar to iOS, but with Android-specific tools)  
  1. **Technology Choice & Setup (1-2 weeks)** (Native Kotlin/Java, or cross-platform)  
  2. **Basic UI/UX Design for Mobile MVP (1-2 weeks)** (Adapt iOS designs or create new ones for Android conventions)  
  3. **API Integration Layer (1-2 weeks)**  
  4. **User Authentication (Mobile) (1 week)**  
  5. **Daily Reading Log Input (Mobile) (2-3 weeks)**  
  6. **Streak & History Display (Mobile) (2-3 weeks)**  
  7. **Basic Offline Support (Consideration) (1-2 weeks)**  
  8. **Testing on Devices & Emulators (1-2 weeks)**  
  9. **Play Store Submission Prep (1 week)**

## **Phase 7: Ongoing Maintenance & Iteration (Both Web & Mobile)**

* **Goal:** Continuously improve the app based on user feedback, fix bugs, update dependencies, and add new features from the "Bible App Ideas" backlog.  
* **Estimated Time:** Ongoing  
* **Tasks:**  
  * User feedback collection and analysis.  
  * Bug fixing and performance monitoring.  
  * Regular dependency updates (Laravel, OS, libraries).  
  * Security audits and updates.  
  * Iterative development of new features (e.g., advanced reading plans, community features, more detailed insights, advanced goal types, etc.).  
  * Marketing and user growth efforts.

**Important Considerations:**

* **Flexibility:** This is a roadmap, not a rigid plan. Be prepared to adapt based on user feedback, technical challenges, and changing priorities.  
* **User Feedback:** Integrate user feedback loops early and often, especially after the Web MVP launch.  
* **Testing:** Don't skimp on testing. Automated tests will save you a lot of time in the long run.  
* **Simplicity:** Especially in early phases, always ask if a feature can be simplified or deferred to keep momentum.  
* **Burnout:** As a solo developer, pace yourself to avoid burnout. Break tasks into smaller, manageable chunks.

This roadmap should give you a solid starting point and a vision for how the project can evolve. Good luck\!