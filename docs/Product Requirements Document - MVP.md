# Bible Reading Habit Builder - Product Requirements Document (MVP)

## Overview

The Bible Reading Habit Builder is an application designed to help users establish and maintain consistent Bible reading habits. The core loop of the application is: **Read → Log → See Progress (Streak/History) → Motivation to Read Again**.

This document outlines the specific requirements for the Minimum Viable Product (MVP) phase, including user stories, user journey maps, acceptance criteria, and prioritization framework.

## Core Value Proposition

**Help users track and build consistency in their Bible reading habits through simple logging and motivating progress visualization.**

## User Stories

### Authentication

1. **As a new user**, I want to sign up for an account, so that I can start tracking my Bible reading habit.
   - **Acceptance Criteria:**
     - User can register with email and password
     - Email validation is performed
     - Password must meet security requirements (8+ characters, including a number)
     - User receives a welcome message after successful registration

2. **As a returning user**, I want to log in to my account, so that I can access my reading history and progress.
   - **Acceptance Criteria:**
     - User can log in with email and password
     - Incorrect credentials result in appropriate error messages
     - User remains logged in unless they explicitly log out
     - Password reset functionality is available

### Daily Reading Log

3. **As a daily Bible reader**, I want to record whole chapters I've read today, so that I can track my reading history.
   - **Acceptance Criteria:**
     - User can select Bible book from a dropdown of all 66 Bible books
     - User can select chapter from a dynamic dropdown that updates based on the selected book
     - The date defaults to the current day
     - System validates all inputs to ensure they are valid Bible references
     - User receives confirmation when the entry is successfully saved
     - Reading logs are restricted to whole chapters only for simplicity in the MVP

4. **As a thoughtful reader**, I want to add simple notes to my reading logs, so that I can remember key insights or questions.
   - **Acceptance Criteria:**
     - Notes field is available when logging a reading
     - Notes are saved alongside the reading entry
     - Notes have a reasonable character limit (500 characters)
     - Notes are displayed when viewing past reading logs

### Streak Tracking

5. **As a daily Bible reader**, I want to see my current streak so that I feel motivated to continue reading.
   - **Acceptance Criteria:**
     - Current streak (consecutive days with logged readings) is prominently displayed on the dashboard
     - Streak is automatically updated when a new reading is logged
     - Streak counter reflects the user's local time zone for accurate daily tracking
     - A 1-day grace period is implemented (streak continues if user reads either today OR yesterday)
     - Streak dates are normalized to the start of day to avoid timezone issues
     - Streak is visually emphasized (e.g., with color, size, or placement)

6. **As a dedicated reader**, I want to see my longest streak ever, so that I can work toward breaking my personal record.
   - **Acceptance Criteria:**
     - Longest ever streak is calculated and displayed
     - This statistic updates when the current streak exceeds the previous record
     - The UI distinguishes between current streak and record streak

### Reading History Visualization

7. **As a visual learner**, I want to see a calendar visualization of my reading history, so I can identify patterns and gaps in my habit.
   - **Acceptance Criteria:**
     - Calendar view displays days with logged readings in a visually distinct way
     - Calendar should resemble GitHub's contribution graph with color-coded squares
     - Calendar is responsive and readable on both desktop and mobile devices
     - Calendar is visible on the user's dashboard
     - User can identify the specific dates when readings were logged
     - Calendar refreshes via HTMX when new readings are logged without requiring full page reload

8. **As an analytical reader**, I want to see statistics about my Bible reading, so I can understand my progress over time.
   - **Acceptance Criteria:**
     - Dashboard shows summary statistics including:
       - Total number of logged readings
       - First and most recent reading dates
       - Books started vs. books completed counts
       - Overall Bible reading progress visualization
     - Book Completion Grid displays all Bible books in canonical order
     - Color-coding indicates completion status (not started: light gray, in progress: blue, completed: green)
     - Statistics are automatically updated when new readings are logged
     - Statistics dynamically refresh via HTMX without requiring full page reload
     - Visualization provides a clear indication of progress through the entire Bible

9. **As a French-speaking user**, I want to use the application in French, so I can engage with the content in my preferred language.
   - **Acceptance Criteria:**
     - System provides a French language option
     - All UI elements are translated into French
     - French language support does not compromise existing functionality
     - User can switch between languages seamlessly

10. **As a consistent reader**, I want to see weekly statistics about my Bible reading, so I can track my week-over-week progress. *(Medium-Priority: Post-MVP Phase 2)*
   - **Acceptance Criteria:**
     - System generates week-by-week reading summaries
     - Dashboard shows number of chapters read per week
     - Dashboard displays weekly reading consistency percentage
     - System identifies strongest and weakest reading days in the week
     - System compares current week to previous weeks
     - Weekly statistics are accessible via a dedicated section on the dashboard
     - Statistics update automatically when new readings are logged
     - Weekly statistics can be viewed for current week and past weeks
     - Visualization indicates patterns in weekly reading habits

### Responsive Design

10. **As a mobile user**, I want to access the app on my smartphone, so I can log readings when I don't have my computer.
   - **Acceptance Criteria:**
     - All core features are functional on mobile screen sizes
     - UI elements adapt appropriately to smaller screens
     - Touch targets are appropriately sized for mobile interaction
     - Text is readable without zooming on mobile devices

11. **As a desktop user**, I want an optimized experience on larger screens, so I can efficiently manage my reading logs.
    - **Acceptance Criteria:**
      - Layout takes advantage of additional screen space on desktop
      - Navigation is intuitive and accessible
      - Dashboard presents comprehensive information without overwhelming the user
      - Performance is smooth regardless of device

## User Journey Maps

### New User Journey

1. **Discovery & Decision**
   - User discovers the app through search, recommendation, or other channels
   - User evaluates whether the app meets their needs for tracking Bible reading

2. **Registration**
   - User creates an account with email and password
   - User receives welcome message with brief orientation to the app's purpose

3. **First-time Experience**
   - User is directed to their empty dashboard
   - User notices the prominent "Log Reading" button
   - User understands that they need to log their first reading to start building a streak

4. **First Reading Log**
   - User clicks "Log Reading" button
   - User enters their first Bible passage (e.g., "John 1")
   - User optionally adds notes about what they read
   - User submits the log and returns to the dashboard

5. **Initial Engagement**
   - User sees their streak counter is now at 1
   - User sees the calendar has one marked day
   - User understands the core loop: read Bible, log reading, see progress

6. **Establishing the Habit**
   - User returns to the app the next day
   - User logs another reading
   - User sees streak increase to 2 and additional mark on calendar
   - User begins to associate the app with their daily Bible reading routine

### Returning User Journey

1. **Daily Return**
   - User returns to the app after completing their Bible reading
   - User logs in if not already authenticated
   - User is presented with their dashboard showing current streak and history

2. **Logging Reading**
   - User clicks "Log Reading"
   - Form is pre-populated with current date
   - User enters passage details and optional notes
   - User submits and sees immediate update to streak and calendar

3. **Progress Review**
   - User scrolls through dashboard to review statistics
   - User examines calendar visualization to see pattern of consistency
   - User takes pride in visible progress and growing streak

4. **Motivation Reinforcement**
   - User sees streak number increase
   - User feels accountability to maintain the streak
   - User makes mental commitment to read again tomorrow

5. **Habit Integration**
   - Over time, checking the app becomes part of user's daily routine
   - User develops intrinsic motivation to maintain the streak
   - User associates positive feelings with consistent Bible reading

## Acceptance Criteria for MVP Features

### 1. User Authentication

- **Registration:**
  - System accepts valid email/password combinations
  - System rejects invalid email formats
  - System enforces password complexity requirements
  - System creates new user account in database
  - System prevents duplicate email registrations

- **Login:**
  - System authenticates valid credentials
  - System rejects invalid credentials with helpful error messages
  - System maintains user session appropriately
  - System provides logout functionality

### 2. Daily Reading Log Input

- **Bible Passage Entry:**
  - System accepts flexible text input for passages
  - Input validates against basic Bible reference patterns
  - System stores passage reference in standardized format

- **Date Handling:**
  - System defaults to current date
  - System prevents future-dated entries
  - System uses user's local timezone for date determination

- **Notes Functionality:**
  - System accepts and stores text notes with reading logs
  - Notes field is optional
  - Notes have appropriate character limits
  - Notes are displayed with reading history

### 3. Streak Calculation & Display

- **Streak Logic:**
  - System accurately counts consecutive days with readings
  - Streak breaks when a day is missed
  - Streak is calculated based on user's local timezone
  - Streak updates immediately after new log entry

- **Visual Presentation:**
  - Current streak is prominently displayed on dashboard
  - Streak counter has visual emphasis (size, color, position)
  - Animation or visual feedback when streak increases

### 4. History Visualization (Calendar)

- **Calendar Display:**
  - System displays a calendar-like visualization (similar to GitHub contribution graph)
  - Days with reading logs are visually distinct
  - Calendar shows at least 3 months of history
  - Calendar is responsive and adapts to different screen sizes

- **Interaction:**
  - Calendar squares have hover/tap states showing the date
  - Optionally, clicking/tapping a day shows reading details for that day

### 5. Basic Responsive Design

- **Mobile Experience:**
  - All core functionality works on mobile devices
  - Forms are usable on touch screens
  - Navigation is touch-friendly
  - Text is readable without zooming

- **Desktop Experience:**
  - Layout optimizes larger screen space
  - Dashboard presents comprehensive information
  - Navigation is intuitive and efficient
  - Visual polish creates a professional appearance

### 6. Advanced Statistics

- **Calculation:**
  - System accurately calculates longest streak ever
  - System tracks and displays books read (partially or completely)
  - System visualizes reading progress across the entire Bible
  - Statistics update with new reading logs

- **Presentation:**
  - Statistics are clearly presented with appropriate labels
  - Visualizations are intuitive and meaningful
  - Statistics section has consistent styling with the rest of the app
  - Information hierarchy prioritizes most important metrics

## Prioritization Framework

The MVP features have been prioritized based on the following framework:

### Value Drivers
1. **Core Value Delivery:** How essential is this feature to delivering the core value proposition of building Bible reading consistency?
2. **User Motivation:** How significantly does this feature contribute to user motivation and habit formation?
3. **Technical Feasibility:** How straightforward is this feature to implement given the technology stack and resources?
4. **Development Efficiency:** How much value does this feature provide relative to development effort?

### Priority Levels

**Level 1: Must-Have (MVP Launch Blockers)**
- Features without which the product cannot deliver its core value proposition
- Features that are fundamental to the user experience
- Features that enable basic product functionality

**Level 2: High-Priority (Strong MVP Enhancers)**
- Features that significantly enhance the core experience
- Features that greatly increase motivation and engagement
- Features that are relatively straightforward to implement with high value

**Level 3: Medium-Priority (Nice-to-Have)**
- Features that enhance but are not critical to the MVP
- Features that can be simplified or partially implemented for MVP
- Features that provide incremental value over must-haves

### Feature Prioritization

| Feature | Priority Level | Rationale |
|---------|---------------|-----------|
| User Authentication | 1: Must-Have | Essential to save individual user progress; foundational for all personalized features |
| Daily Reading Log Input | 1: Must-Have | The primary user action; enables the core functionality of recording Bible reading |
| Streak Calculation & Display | 1: Must-Have | The core gamification mechanic that drives consistent usage; immediate feedback loop |
| History Visualization (Calendar) | 2: High-Priority | Powerful visual motivator; reinforces progress; relatively simple implementation |
| Basic Responsive Design | 2: High-Priority | Essential for mobile users who may log readings throughout the day on different devices |
| Notes Functionality | 2: High-Priority | Adds significant value with minimal technical complexity; captures meaningful reflections |
| Basic Book Completion Tracking | 2: High-Priority | Provides essential progress visualization; implemented with denormalized table for performance |
| French Language Support | 2: High-Priority | Essential for serving the Quebec user base; enables broader adoption in francophone communities |
| Weekly Statistics | 3: Medium-Priority | Enhances motivation but not essential for MVP; deferred to Phase 2 (post-MVP) |
| Advanced Statistics | 3: Medium-Priority | Enhances motivation but not essential for the core experience; can be added incrementally |

### Features Excluded from MVP

The following features have been deliberately excluded from the MVP to focus development efforts and deliver value more quickly:

- **Reading Plans:** While valuable, structured reading plans add significant complexity and can be added post-MVP
- **Goal Setting:** Beyond the implicit goal of streak maintenance, custom goal setting adds UI and data complexity
- **Enhanced Notes Features:** Advanced features like tagging can be added once basic notes functionality is validated
- **Streak Freeze / Grace Days:** The core streak mechanic should be validated before adding these modifications
- **Badges / Achievements:** These engagement features can be added after validating the core motivational mechanic
- **Community Features:** Social features add considerable complexity and are better suited for later phases
- **Bible Version Selection:** Integration with Bible text APIs adds complexity and potential licensing issues
- **Reminders / Notifications:** Push notification infrastructure adds technical complexity
- **Data Export:** Not essential for the core experience; can be added in response to user feedback

### Rationale for this Prioritization

1. **Fastest Path to Core Value:** The prioritized features deliver the essential log-track-visualize loop that encourages consistency
2. **Technical Simplicity:** The MVP focuses on features that can be built quickly with the chosen tech stack
3. **Validation Focus:** The core features will validate whether the streak/calendar mechanic resonates for Bible reading tracking
4. **Foundation for Iteration:** The MVP builds the essential data structures upon which all future features can be developed
5. **User-Centered:** Prioritization focuses on the features most likely to drive user adoption and habit formation

By launching with this focused set of features, we can:
- Deliver a working product quickly
- Gather real user feedback
- Validate core assumptions
- Begin the cycle of iterative improvement based on actual usage data
- Avoid building complex features that may not align with user needs
