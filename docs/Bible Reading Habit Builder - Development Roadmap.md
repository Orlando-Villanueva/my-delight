# **Bible Reading Habit Builder - Development Roadmap**

**Core Tech Stack:**

* **Backend:** Laravel  
* **Database:** PostgreSQL (via Railway.app)  
* **Web Frontend:** HTMX + Alpine.js  
* **Deployment:** Railway.app (PaaS)
* **Development Workflow:** Continuous deployment with feature-based PR structure

## **MVP Development Plan**

**Goal:** Launch the core "Read → Log → See Progress" loop to help users establish Bible reading habits.

**Timeline:** 8 Weeks

### **Development Workflow**

* **Feature-Based PRs:** Each feature developed in a separate branch
* **Continuous Deployment:** Every pushed commit to the main branch triggers automatic deployment to production
* **HTMX First Approach:** Focus on HTMX endpoints returning HTML fragments for the MVP (JSON API endpoints deferred to post-MVP)
* **Testing Integration:** 1-2 user testing sessions during development
* **PR Structure:** Clear, focused PRs with descriptive titles and descriptions
* **PR Reviews:** Self-review required before merging

### **Week 1: Foundation & Environment Setup**

**PR 1: Project Scaffolding (3-4 days)**
* Set up Laravel project with HTMX + Alpine.js
* Configure local development environment (using Laravel Herd for local, Railway for production)
* Initialize Git repository
* Implement basic responsive layout structure

**PR 2: Deployment Pipeline (1-2 days)**
* Set up Railway.app infrastructure
* Provision PostgreSQL database
* Configure CI/CD pipeline with GitHub
* Set up automatic deployment for all pushed commits
* Configure deployment environments (staging/production)

**PR 3: Database Foundations (2-3 days)**
* Design core database schema
* Create migrations for users, reading_logs, book_progress tables
* Define Eloquent models
* Create Bible reference configuration file with static data for all 66 books

### **Week 2: Authentication & UI Framework**

**PR 4: User Authentication (3-4 days)**
* Implement registration system with email validation
* Create login functionality with remember me option
* Add password reset flow
* Build authentication views (HTMX compatible)

**PR 5: UI Framework & Design System (3-4 days)**
* Implement Tailwind CSS configuration
* Create base component library
* Set up design tokens (colors, typography, spacing)
* Develop responsive navigation components

### **Week 3: Reading Log Core**

**PR 6: Bible Reference System (2-3 days)**
* Implement Bible reference service
* Create book and chapter data structure
* Build dynamic chapter selection logic
* Set up reference validation

**PR 7: Reading Log Form (3-4 days)**
* Develop two-step Bible passage selector
* Create notes input with validation
* Build submission flow with HTMX integration
* Implement success/error handling

### **Week 4: Streak System & First User Testing**

**PR 8: Streak Calculation System (3-4 days)**
* Develop streak calculation service
* Implement 1-day grace period logic
* Create longest streak tracking
* Build streak UI components

**PR 9: Dashboard Integration (2-3 days)**
* Create user dashboard layout
* Integrate streak display
* Add quick-log entry point
* Build initial statistics display

**[User Testing Session #1]** - Focus on core functionality

### **Week 5: Visualization & Progress Tracking**

**PR 10: Calendar Visualization (3-4 days)**
* Implement GitHub-style contribution calendar
* Create date-based coloring system
* Build hover interaction for reading details
* Add HTMX refresh behavior

**PR 11: Book Completion Tracking (3-4 days)**
* Create book progress service
* Implement completion percentage calculation
* Build book grid visualization
* Add progress update triggers

### **Week 6: Statistics & Multilingual Support**

**PR 12: Reading Statistics (3-4 days)**
* Implement core statistics calculations:
  * Total chapters read
  * Books started vs. completed count
  * Total days with reading activity
* Create statistics cards and visualizations
* Build caching layer for performance
* Add dynamic updates via HTMX

**PR 13: French Language Support (2-3 days)**
* Set up Laravel localization
* Create translation files
* Add language selector
* Implement locale-aware formatting
* Extend Bible reference configuration to support French book names

### **Week 7: Performance & Accessibility**

**PR 14: Caching & Performance Optimization (3-4 days)**
* Set up Redis for caching (via Railway.app)
* Implement tiered caching strategy:
  * Application-level cache for user statistics and streak data
  * Cache Bible reference metadata (book/chapter counts)
  * Cache reading history summaries
* Configure cache invalidation strategies:
  * Time-based TTL (5-15 min for frequent data, 24+ hours for static data)
  * Event-based invalidation (when new readings are logged)
  * Selective invalidation using cache tags
* Optimize database queries with proper indexing
* Configure asset optimization (minification, compression)
* Add HTMX loading indicators for better UX

**PR 15: Accessibility Implementation (3-4 days)**
* Ensure WCAG 2.1 AA compliance
* Add ARIA labels and roles
* Implement keyboard navigation
* Test with screen readers

**[User Testing Session #2]** - Focus on UX refinement

### **Week 8: Final Polish & Deployment**

**PR 16: UX Refinements (2-3 days)**
* Address user testing feedback
* Polish animations and transitions
* Refine mobile experience
* Enhance form validation feedback

**PR 17: Final Testing & QA (2-3 days)**
* Cross-browser compatibility testing
* Mobile responsiveness validation
* Security audit
* Performance benchmarking

**PR 18: Production Monitoring & Analytics (2-3 days)**
* Configure production environment
* Set up error tracking (Sentry or similar)
* Implement database backup strategy
* Deploy basic analytics tracking (optional):
  * Page view and user action monitoring
  * Reading log creation tracking
  * Basic user engagement metrics
* Complete final production deployment

## **Key Milestones**

1. **End of Week 2:** Complete authentication and base UI
2. **End of Week 4:** Core reading log and streak functionality
3. **End of Week 6:** All main features implemented
4. **End of Week 8:** Production-ready application

## **Future Phases**

After validating the MVP with real users, future phases will expand the application with:

* Advanced statistics and insights
* Reading plans and goals
* Enhanced habit formation mechanics
* Mobile applications (iOS/Android)

_Note: This roadmap focuses on the MVP phase only. Future phases will be planned based on user feedback and validation of core concepts._