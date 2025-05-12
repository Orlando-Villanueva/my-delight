# **Bible Reading Habit Builder \- Development Roadmap**

**Core Tech Stack:**

* **Backend:** Laravel  
* **Database:** Co-located Managed Database (e.g., PostgreSQL via PaaS)  
* **Web Frontend:** HTMX \+ Alpine.js (served by Laravel)  
* **Deployment (Web App & Backend):** PaaS (e.g., Render, Railway.app)

## **Phase 1: MVP Web App \- Foundation & Core Loop**

* **Goal:** Launch the core "Read \-\> Log \-\> See Progress (Streak/History)" loop with essential features as defined in the "Bible App MVP" document. Validate the concept quickly.  
* **Estimated Time:** 4-7 Weeks  
* **Tasks:**  
  1. **Project Setup & Environment (3-5 days)**  
     * Set up a new Laravel project.  
     * Configure local development environment (e.g., Laravel Herd, Laragon, Docker with Sail).  
     * Initialize Git repository (e.g., on GitHub, GitLab).  
     * Select and set up a PaaS (e.g., Render, Railway.app) and provision a managed PostgreSQL database.  
     * Configure basic deployment pipeline (e.g., Git push to deploy to a staging/development environment on the PaaS).  
  2. **Database Schema & Migrations \- MVP (2-3 days)**  
     * Design database schema for users, reading\_logs (user\_id, date\_read, passage\_text, notes\_text).  
     * Create Laravel migrations for these tables.  
     * Define Eloquent models for User and ReadingLog.  
  3. **User Authentication (Web) (3-5 days)**  
     * Implement custom email/password registration and login compatible with HTMX + Alpine.js frontend.  
     * Create basic views for login, registration, and password reset that integrate with HTMX approach.  
     * Ensure authenticated users have a dedicated dashboard/home area.  
     * Note: This approach avoids Laravel starter kits as they're not compatible with our HTMX architecture.  
  4. **Daily Reading Log Input (Web) (5-7 days)**  
     * Create a form for users to log their daily reading:  
       * Flexible text input for Bible passage (e.g., "John 3:16-21").  
       * Date selector (defaults to today, consider "today only" for MVP simplicity).  
       * Small plain text area for basic notes.  
     * Develop Laravel controller logic to handle form submission, validate input, and save ReadingLog entries to the database (associated with the authenticated user).  
     * Use HTMX for form submission to provide a smooth UX without full page reloads. Return a success message or an updated part of the page.  
  5. **Streak Calculation & Display (Web) (3-5 days)**  
     * Develop backend logic (e.g., in a Laravel service or model method) to calculate the current reading streak for the authenticated user based on consecutive ReadingLog dates.  
     * Display the current streak prominently on the user's dashboard (e.g., in the header or a dedicated section).  
     * Use HTMX to potentially refresh this display after a new log is entered.  
  6. **History Visualization (Basic Calendar) (Web) (4-6 days)**  
     * Develop backend logic to fetch all ReadingLog dates for the authenticated user.  
     * Create a simple calendar view (e.g., using HTML table or CSS Grid, styled to resemble GitHub's contribution graph) where days with logged readings are visually distinct.  
     * Display this calendar on the user's dashboard.  
  7. **Basic Responsive Design & UI Styling (Ongoing, 3-5 days dedicated)**  
     * Implement basic responsive design using CSS (Flexbox, Grid, Media Queries) to ensure core features are usable on desktop and common mobile screen sizes.  
     * Apply simple, clean styling. Consider a lightweight CSS framework like Pico.css or Tailwind CSS (if comfortable with its utility-first approach and setup).  
  8. **Testing & Bug Fixing (MVP Scope) (3-4 days)**  
     * Manual testing of all MVP features.  
     * Write basic Laravel feature tests for critical paths (authentication, log creation).  
  9. **Deployment to Production & Basic Monitoring (1-2 days)**  
     * Deploy the MVP to the production environment on your chosen PaaS.  
     * Set up basic error tracking (e.g., Sentry free tier, Laravel's built-in logging).

## **Phase 2: Web App \- Core Feature Enhancements**

* **Goal:** Incorporate key habit-forming features from the "Bible App Ideas" document that build directly on the MVP.  
* **Estimated Time:** 5-8 Weeks  
* **Tasks:**  
  1. **Reading Plans Integration (Basic) (7-10 days)**  
     * Database: Add tables for reading\_plans (name, description, list of passages/days) and user\_reading\_plan\_progress (user\_id, plan\_id, current\_day, completed\_passages).  
     * Backend: Seed some built-in reading plans. Logic for users to select a plan. Logic to display today's reading for the active plan. Logic to mark plan readings as complete (could be tied to logging a specific passage).  
     * Frontend: UI for users to browse and select plans. Display current plan and today's reading. Basic progress visualization within a plan.  
  2. **Goal Setting & Tracking (Basic) (5-7 days)**  
     * Database: Add table for user\_goals (user\_id, goal\_type \[e.g., chapters\_per\_day, finish\_book\_by\_date\], target\_value, current\_progress, start\_date, end\_date).  
     * Backend: Logic for users to create simple goals (e.g., "Read X chapters daily"). Logic to update goal progress based on reading logs.  
     * Frontend: UI for creating and viewing active goals. Basic progress display towards these goals.  
  3. **Enhanced Notes & Reflection (Basic Tagging) (4-6 days)**  
     * Database: Add tags table and a pivot table for reading\_log\_tag.  
     * Backend: Logic to allow users to add tags to their reading log notes. Basic search/filter of notes by tags.  
     * Frontend: UI to add/manage tags during note entry. UI to view notes filtered by tags.  
  4. **"Catch-Up" / Grace Mechanism (Streak Forgiveness \- Simple) (3-5 days)**  
     * Backend: Implement a simple "log for yesterday" feature (e.g., allow logging for the previous day until a certain time, like noon).  
     * Alternatively, a very simple "streak freeze" concept (e.g., one per month, manually applied for now or very simply earned).  
     * Frontend: Clear UI for this mechanism if applicable.  
  5. **Bible Version Selection (Tracking Only) (1-2 days)**  
     * Database: Add a bible\_version field to reading\_logs (or user profile if it's a default).  
     * Frontend: Allow users to specify which Bible version they are reading from when logging (simple text input or dropdown).  
  6. **Refined UI/UX based on MVP Feedback (Ongoing, 3-4 days dedicated)**  
     * Incorporate any user feedback from the MVP.  
     * Improve navigation and clarity.  
  7. **Testing & Bug Fixing (Phase 2 Scope) (4-5 days)**  
     * Manual and automated testing for new features.

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
  6. **Accessibility Review & Improvements (2-3 days)**  
     * Review the app for basic accessibility (contrast, keyboard navigation, semantic HTML). Make necessary adjustments.  
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