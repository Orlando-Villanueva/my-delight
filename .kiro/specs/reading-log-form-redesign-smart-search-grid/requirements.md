# Requirements Document

## Introduction

Transform the existing reading log form using a hybrid "Smart Search + Visual Grid" approach that prioritizes speed and mobile-first usability. This design combines autocomplete-based book selection with a dynamic visual chapter grid, eliminating the two major pain points (66-book dropdown scrolling and text-based chapter entry) while delivering maximum user experience improvement with minimal implementation complexity.

This approach is optimized for the actual user journey: users who have just finished reading and want to log their session quickly, knowing exactly what book and chapters they read. The design emphasizes speed, discoverability, and mobile-native interaction patterns.

## Requirements

### Requirement 1

**User Story:** As a user, I want to type and instantly find my Bible book using autocomplete search, so that I can select books in seconds without scrolling through 66 dropdown options.

#### Acceptance Criteria

1. WHEN I access the reading log form THEN there SHALL be a text input field labeled "Bible Book" with premium styling (border-2, rounded-xl, py-3.5) and placeholder "Type book name... (e.g., Genesis, Psa, Matt)"
2. WHEN I focus the book input field THEN an autocomplete suggestion panel SHALL appear below with rounded-xl corners and shadow-xl depth
3. WHEN I type in the book field THEN the system SHALL filter books instantly matching my input
4. WHEN I search THEN the system SHALL match against book names, common abbreviations (gen, psa, matt), and alternative names with fuzzy matching
5. WHEN I type partial text THEN matching portions SHALL be highlighted in orange within the suggestion results
6. WHEN I use keyboard navigation THEN arrow keys (up/down) SHALL navigate through suggestions and Enter SHALL select the focused book
7. WHEN I select a book from suggestions THEN the input SHALL populate with the full book name and suggestions SHALL close
8. WHEN I clear the input field THEN the suggestions SHALL return to the default state showing recent books
9. WHEN no matches are found THEN the system SHALL display "No books found"

### Requirement 2

**User Story:** As a returning user, I want my recently read books to appear at the top of the autocomplete suggestions, so that I can log my regular reading patterns with minimal interaction.

#### Acceptance Criteria

1. WHEN I open the book autocomplete (empty search) THEN there SHALL be a "📖 Recent" section showing my last 3-5 distinct books read
2. WHEN I view recent books THEN each SHALL display book name and relative time (today, yesterday, or [X] days ago)
3. WHEN I click a recent book THEN it SHALL select immediately and optionally pre-select the next expected chapter based on reading pattern
4. WHEN I have no reading history THEN the recent section SHALL be hidden
5. WHEN I search while recent books are visible THEN they SHALL be hidden and replaced with search results
6. WHEN recent books are displayed THEN they SHALL appear above the testament-grouped book list for priority access
7. WHEN I select a recent book THEN the system SHALL track this selection for future "recent books" ordering

### Requirement 3

**User Story:** As a user, I want the autocomplete suggestions to be organized and visually clear, so that I can quickly scan and understand my options even when browsing the full list.

#### Acceptance Criteria

1. WHEN I view autocomplete suggestions THEN books SHALL be organized into "📜 Old Testament" and "✝️ New Testament" sections with clear visual separation
2. WHEN I view book entries THEN each SHALL display book name and chapter count with generous padding (py-3) and rounded corners
3. WHEN I hover over suggestions on desktop THEN there SHALL be clear hover states with subtle background transitions
4. WHEN I view suggestions on mobile THEN each option SHALL have minimum 56px height for comfortable thumb tapping
5. WHEN the suggestion panel is open THEN it SHALL have rounded-xl corners, shadow-xl depth, and maximum height with smooth scrolling
6. WHEN I tap outside the suggestion panel THEN it SHALL close gracefully without selecting anything

### Requirement 4

**User Story:** As a mobile user, I want the book autocomplete to feel native and responsive, so that typing and selecting books is as smooth as using any modern mobile app.

#### Acceptance Criteria

1. WHEN I focus the book input on mobile THEN the virtual keyboard SHALL appear immediately with proper input type (text, not search to avoid unwanted icons)
2. WHEN the keyboard appears THEN the suggestion panel SHALL position itself to remain visible above the keyboard
3. WHEN I type on mobile THEN there SHALL be no input lag or delay in filtering results
4. WHEN I tap a suggestion on mobile THEN there SHALL be immediate visual feedback (brief pressed state) before selection
5. WHEN I select a book on mobile THEN the keyboard SHALL remain open and focus SHALL move to the chapter section
6. WHEN I scroll suggestions on mobile THEN scrolling SHALL use native momentum physics without conflicts
7. WHEN I use the autocomplete THEN it SHALL work smoothly on devices as old as iPhone X (2017) and equivalent Android devices

### Requirement 5

**User Story:** As a user, I want a beautiful visual chapter grid with smart navigation that appears instantly when I select a book, so that I can quickly find and select chapters even in large books like Psalms.

#### Acceptance Criteria

1. WHEN I select a book THEN a chapter selection section SHALL appear below the book field as a bordered container (border-gray-200, rounded-xl)
2. WHEN the chapter container appears THEN it SHALL have a fixed header section (bg-gray-50, border-b) showing "Select Chapter(s) - [Book Name] ([X] chapters)"
3. WHEN I select a book with >30 chapters THEN there SHALL be a "Quick Jump" input field in the header allowing me to type a chapter number to scroll directly to that chapter
4. WHEN I type in the Quick Jump field THEN the grid SHALL auto-scroll to show the typed chapter number and briefly highlight it for visual confirmation
5. WHEN the chapter grid displays THEN it SHALL be in a scrollable area (max-h-[50vh], overflow-y-auto, p-4) with responsive grid (5 columns mobile, 6 tablet, 8-10 desktop) and gap-3 spacing
6. WHEN the grid has more chapters below the visible area THEN there SHALL be a gradient fade at the bottom edge indicating more content
7. WHEN I scroll chapters THEN only the grid area SHALL scroll while the container header (including Quick Jump) remains visible
8. WHEN I view chapter buttons THEN each SHALL be 56×56px (h-14) touch targets with semibold text, rounded-xl corners, and shadow-sm depth
9. WHEN I select a different book THEN the chapter grid SHALL instantly update using pre-loaded Bible configuration data and reset the Quick Jump field
10. WHEN I view unselected chapter buttons THEN they SHALL have bg-gray-50 background with hover:shadow-md elevation
11. WHEN I view selected chapter buttons THEN they SHALL have bg-primary-600 background with white text and shadow-md elevation

### Requirement 6

**User Story:** As a user, I want to select single or multiple chapters by tapping buttons in the visual grid, so that logging complex reading sessions is intuitive and error-free.

#### Acceptance Criteria

1. WHEN I tap an unselected chapter button THEN it SHALL toggle to selected state with orange background (primary-600) and white text
2. WHEN I tap a selected chapter button THEN it SHALL toggle to unselected state and return to default styling
3. WHEN I tap multiple chapters THEN each SHALL toggle independently allowing non-contiguous selections (e.g., chapters 1, 3, 5, 8)
4. WHEN I select chapters THEN there SHALL be a dynamic summary showing intelligent formatting:
   - Contiguous ranges: "Selected: 1-5" (for chapters 1, 2, 3, 4, 5)
   - Non-contiguous: "Selected: 1, 3, 5, 8" (for non-sequential chapters)
   - Mixed: "Selected: 1-3, 5, 8-10" (combining ranges and individual chapters)
5. WHEN I have selections THEN there SHALL be a "Clear All" button to deselect all chapters at once
6. WHEN I make selections THEN the hidden chapter_input field SHALL auto-populate with comma-separated chapter numbers for backend validation (e.g., "1,2,3,5,8")

### Requirement 7

**User Story:** As a user, I want a more compact and streamlined date selection, so that the form focuses on the primary book and chapter selection without unnecessary visual clutter.

#### Acceptance Criteria

1. WHEN I access the form THEN the date selection SHALL be a compact single-line element at the top
2. WHEN no grace period is available THEN the date SHALL simply show "📅 Today (Oct 4, 2025)" without interactive elements
3. WHEN grace period is available THEN there SHALL be a compact toggle or small dropdown showing "Today | Yesterday"
4. WHEN I view the date section THEN it SHALL take maximum 44px vertical height to preserve screen space for book/chapter selection
5. WHEN I switch between today/yesterday THEN only the form date value SHALL change with no visual transitions or layout shifts
6. WHEN the date section is displayed THEN it SHALL have subtle styling that doesn't compete with the book/chapter interface

### Requirement 8

**User Story:** As a user, I want comprehensive keyboard accessibility throughout the entire form, so that I can efficiently navigate and complete the form using only my keyboard.

#### Acceptance Criteria

1. WHEN I use Tab navigation THEN focus SHALL move logically through date selection, book input, Quick Jump (if visible), chapter grid buttons, notes, and submit button
2. WHEN the book input is focused THEN typing SHALL immediately filter suggestions without additional keystrokes
3. WHEN autocomplete suggestions are open THEN arrow keys (up/down) SHALL navigate through options with clear visual focus indicators
4. WHEN I navigate the chapter grid THEN Tab SHALL move forward through chapters in DOM order and Shift+Tab SHALL move backward
5. WHEN I navigate the chapter grid THEN arrow keys SHALL be reserved for autocomplete only (not used for grid navigation to avoid responsive layout complexity)
6. WHEN I use the Quick Jump field THEN I can type a chapter number and press Enter to scroll to and focus that chapter button
7. WHEN I press Space or Enter on focused chapter buttons THEN they SHALL toggle selection state
8. WHEN I press Escape THEN any open autocomplete SHALL close and focus SHALL return to the input field
9. WHEN I use screen readers THEN all interactive elements SHALL have proper ARIA labels and state announcements (e.g., "Chapter 23, button, not selected")
10. WHEN I navigate with keyboard THEN focus indicators SHALL be highly visible with 2px orange outlines and proper contrast
11. WHEN selection states change THEN screen readers SHALL announce "Chapter X selected" or "Chapter X deselected"

**Note:** Arrow key navigation is intentionally simplified to avoid complexity with responsive grid layouts. Tab/Shift-Tab provides predictable, consistent keyboard navigation. Quick Jump field offers fast access to specific chapters in large books.

### Requirement 9

**User Story:** As a user, I want essential visual feedback and smooth transitions, so that the interface helps me understand my actions without unnecessary complexity.

#### Acceptance Criteria

1. WHEN I select a book THEN the chapter grid SHALL appear with a simple fade-in transition (150ms)
2. WHEN I switch between books THEN the chapter grid SHALL update with instant re-rendering (no transition to maintain speed)
3. WHEN I select/deselect chapters THEN there SHALL be immediate visual state change (background color) with simple CSS transition (100ms)
4. WHEN autocomplete results update THEN they SHALL update instantly without layout shift animations
5. WHEN users have reduced motion preferences (prefers-reduced-motion) THEN all transitions SHALL be removed (transition: none)
6. WHEN the form loads THEN there SHALL be no loading animations - progressive content rendering only

**Note:** Advanced animations (micro-interactions, staggered effects, slide-ups, scale pulses) are deferred for post-launch polish based on user feedback and performance metrics on target devices.

### Requirement 10

**User Story:** As a user, I want clear visual hierarchy and progress indication throughout the form, so that I always understand my current step and what's been completed.

#### Acceptance Criteria

1. WHEN I view the form THEN there SHALL be clear visual hierarchy showing the progression: Date → Book → Chapters → Notes
2. WHEN I complete the book selection THEN the book field SHALL show the selected book name
3. WHEN the chapter grid appears THEN it SHALL be contained in a bordered container (rounded-xl, border-gray-200) with fixed header (including Quick Jump for large books) and scrollable grid area
4. WHEN I scroll through chapters THEN only the grid area SHALL scroll (max-h-[50vh] overflow-y-auto) while book autocomplete and grid header remain visible
5. WHEN I make chapter selections THEN the selection summary SHALL be prominently displayed and update in real-time
6. WHEN errors occur THEN they SHALL be contextually placed near the relevant field with clear correction guidance

### Requirement 11

**User Story:** As a mobile user, I want the entire form to feel native and intuitive with perfect touch interactions, so that logging my reading on mobile is as smooth as using a dedicated Bible app.

#### Acceptance Criteria

1. WHEN I use the form on mobile THEN all interactive elements SHALL meet minimum 44px touch target requirements with 8px spacing
2. WHEN I tap any interactive element THEN there SHALL be immediate visual feedback with subtle pressed states (CSS active pseudo-class)
3. WHEN I scroll through any section THEN scrolling SHALL be smooth with proper momentum and no horizontal overflow
4. WHEN I interact with chapter buttons THEN visual state changes SHALL provide clear feedback (haptic feedback deferred for polish phase)
5. WHEN I rotate my device THEN the responsive grid SHALL adapt seamlessly between portrait and landscape orientations
6. WHEN I tap outside focused elements THEN there SHALL be proper focus management and keyboard dismissal
7. WHEN I use gesture navigation THEN there SHALL be no conflicts with system gestures (proper touch event handling)
8. WHEN the form loads on mobile THEN initial render SHALL be optimized for mobile processors with minimal JavaScript overhead

### Requirement 12

**User Story:** As a user, I want intelligent error handling and validation that works seamlessly with the visual interface, so that I receive clear guidance when issues occur.

#### Acceptance Criteria

1. WHEN I submit without selecting a book THEN there SHALL be a clear error message near the book field with red accent color
2. WHEN I submit without selecting chapters THEN the chapter grid SHALL highlight with red border and show "Please select at least one chapter"
3. WHEN server validation fails THEN errors SHALL be displayed contextually at the relevant step without losing my selections
4. WHEN I correct validation errors THEN error messages SHALL disappear immediately upon valid input
5. WHEN form submission fails THEN all my selections SHALL be preserved using Laravel's old() helper
6. WHEN network errors occur THEN there SHALL be appropriate retry mechanisms with clear user-friendly messages

### Requirement 13

**User Story:** As a user, I want a celebratory and informative success experience after logging my reading, so that I feel accomplished, see my progress, and am motivated to continue my reading habit tomorrow.

#### Acceptance Criteria

1. WHEN I successfully submit a reading log THEN the server SHALL return modal HTML only (not full page replacement)
2. WHEN the modal HTML is returned THEN HTMX SHALL append it to the document body (hx-target="body" hx-swap="beforeend")
3. WHEN the success modal appears THEN it SHALL display as a centered overlay with semi-transparent backdrop (60% black with blur effect)
4. WHEN I view the success modal THEN it SHALL display a celebration header with "✨ Reading Logged! ✨" and the specific book/chapters I logged
5. WHEN the success modal shows AND this is my first log of the day THEN it SHALL include my current streak information (e.g., "🔥 7-day streak maintained!")
6. WHEN the success modal shows AND this is a subsequent log on the same day THEN it SHALL NOT display streak information (streak already extended by first log)
7. WHEN the success modal displays AND the logged chapters included new chapters never read before THEN it SHALL show my book progress (e.g., "Genesis 10% complete - 5/50 chapters")
8. WHEN the success modal displays AND all logged chapters were previously read THEN it SHALL NOT display book progress (progress unchanged)
9. WHEN I successfully submit the form THEN the form SHALL automatically reset to empty state (via HTMX afterRequest event) while the success modal appears
10. WHEN I view the success modal THEN there SHALL be action buttons for "View Progress" (navigates to dashboard) and "Done" (closes modal)
11. WHEN the modal is open THEN I SHALL be able to close it by clicking outside the modal, pressing ESC key, or clicking "Done"
12. WHEN I close the success modal THEN the reset form underneath SHALL be revealed ready for another log entry
13. WHEN the success modal appears THEN it SHALL fade in with a simple opacity transition (200ms) on all devices
14. WHEN the success modal displays on mobile THEN it SHALL take approximately 80% screen width with proper spacing for thumb interaction
15. WHEN the success modal displays on desktop THEN it SHALL have a maximum width of 480px and be centered both horizontally and vertically
16. WHEN screen readers are used THEN the modal SHALL have proper ARIA labels (role="dialog", aria-modal="true", aria-labelledby) and announce the success state
17. WHEN the modal appears THEN it SHALL use the same color scheme and styling as the rest of the application (orange primary accent, dark mode support)

**Note:** Milestone detection and celebration (book completions, chapter milestones, testament achievements) is deferred to a separate epic (DEL-171) to allow faster MVP delivery of the form redesign.

### Requirement 14

**User Story:** As a developer, I want the smart search + visual grid interface to integrate seamlessly with the existing HTMX architecture while maintaining excellent performance and code maintainability.

#### Acceptance Criteria

1. WHEN the form loads THEN initial render time SHALL be under 200ms including all interactive components and Bible configuration data
2. WHEN I type in the autocomplete THEN filtering SHALL respond within 50ms for smooth real-time feedback
3. WHEN I switch books THEN the chapter grid SHALL update instantly using pre-loaded Bible data from config/bible.php without server requests
4. WHEN simple transitions run THEN they SHALL use efficient CSS transitions without JavaScript-based animation libraries
5. WHEN I submit the form THEN all existing Laravel validation and HTMX error handling SHALL work identically to current implementation
6. WHEN I extend the interface THEN code SHALL be modular with clear separation between autocomplete logic, chapter grid, and form submission
7. WHEN the component is implemented THEN it SHALL use Alpine.js for state management following the project's existing patterns
8. WHEN Bible book data is needed THEN it SHALL be passed from BibleReferenceService through the controller to avoid hardcoding

### Requirement 15 (Contextual Help - DEFERRED for Post-MVP)

**User Story:** As a user, I want the form to provide contextual help and smart suggestions based on my reading patterns, so that the interface anticipates my needs and accelerates my workflow.

**Status:** DEFERRED for post-MVP based on user feedback. MVP focuses on core Quick Jump navigation and Recent Books (Requirement 2).

#### Acceptance Criteria (Deferred)

1. WHEN I select a book with partial progress THEN there SHALL be a "Continue from Ch [X]" quick action button that pre-selects the next unread chapter
2. WHEN I view the autocomplete THEN frequently read books SHALL be prioritized in search results below recent books

**Rationale:** Quick Jump (Requirement 5) and Recent Books (Requirement 2) already provide efficient navigation. Additional contextual help can be added based on user feedback after launch.

### Requirement 16 (Performance & Optimization)

**User Story:** As a user, I want the smart search + visual grid interface to be fast and responsive even with rich interactions, so that the enhanced experience doesn't slow down my reading logging workflow.

#### Acceptance Criteria

1. WHEN I measure performance THEN Core Web Vitals SHALL meet or exceed current form performance benchmarks

### Requirement 17 (Testing & Quality)

**User Story:** As a developer, I want backend testing coverage for services and form submission, so that the enhanced form is reliable and doesn't introduce regressions.

#### Acceptance Criteria

1. WHEN testing form submission THEN tests SHALL verify all existing Laravel validation rules continue to work correctly
2. WHEN testing edge cases THEN tests SHALL cover books with many chapters (Psalms: 150), single chapter books (Obadiah), chapter ranges, and invalid inputs
3. WHEN testing success modal data THEN tests SHALL verify ReadingLogService returns correct streak and book progress data
4. WHEN running tests THEN all existing reading log tests SHALL continue to pass without modification

**Note:** MilestoneService testing is deferred to the separate milestone epic (DEL-171).

### Requirement 18 (Loading & Submission States)

**User Story:** As a user, I want clear visual feedback when submitting the form, so that I know my reading log is being processed and don't accidentally submit twice.

#### Acceptance Criteria

1. WHEN I click the submit button THEN the button SHALL display a loading spinner and change text to "Submitting..."
2. WHEN the form is submitting THEN the submit button SHALL be disabled to prevent double-submission
3. WHEN the form is submitting THEN HTMX's built-in `htmx-request` class SHALL be used for loading state styling
4. WHEN the server response is received THEN the loading state SHALL clear and the response SHALL be swapped in (success modal or validation errors)
5. WHEN the form initially loads THEN it SHALL render with all Bible data embedded in the HTML (no separate data fetching or loading spinners needed)
6. WHEN Alpine.js hydrates client-side interactions THEN it SHALL use pre-loaded Bible configuration data from the server-rendered HTML

**Note:** The form is server-rendered with Bible data embedded. No skeleton screens are needed for initial page load. Only form submission requires a loading indicator.

### Requirement 19 (Controller Refactoring - Post-MVP)

**User Story:** As a developer maintaining the reading log form, I want the controller to follow HTMX-native patterns with minimal complexity, so that the codebase remains maintainable and consistent with the application's architecture.

**Status:** DEFERRED until after Phase 1-5 completion. This refactoring should only happen once the new form components (autocomplete, chapter grid, success modal) are fully functional and tested.

#### Acceptance Criteria

1. WHEN form submission succeeds with HTMX request THEN the controller SHALL return only the success modal HTML (not the entire form)
2. WHEN form submission succeeds THEN form reset SHALL be handled client-side via HTMX `@htmx:after-request` event (not server-side form re-rendering)
3. WHEN validation errors occur THEN the controller SHALL leverage Laravel's default validation with HTMX error handling (not manual form re-rendering)
4. WHEN implementing the controller THEN there SHALL be no duplicate data fetching logic across multiple catch blocks
5. WHEN implementing error handling THEN the controller SHALL follow DRY principles with consolidated error responses
6. WHEN the refactor is complete THEN the `store()` method SHALL be approximately 15 lines (reduced from ~130 lines)
7. WHEN the refactor is complete THEN all existing tests SHALL be updated to match the new simplified flow

**Current Issues (To Be Fixed)**:
- Success response returns entire form with fresh data instead of just success modal
- Three separate catch blocks (ValidationException, InvalidArgumentException, QueryException) duplicate identical form re-rendering logic
- Controller manually fetches books, formContext, recentBooks in multiple places
- Violates DRY principle and doesn't align with HTMX-native error handling

**Rationale:** The current implementation is a transitional state between the old form-swap pattern and the new modal-overlay pattern. Once the new form is functional, this over-engineering becomes technical debt. Refactoring after MVP ensures we can test the new flow end-to-end before removing old code.

**Implementation Details:** See design.md section "Controller Update" for target implementation pattern.

## Design Principles

### Mobile-First Priorities
1. **Speed above all**: Minimize taps and typing required to log reading
2. **Native feel**: Use familiar mobile interaction patterns (autocomplete, visual grids)
3. **Touch-optimized**: All interactive elements meet or exceed platform touch target guidelines
4. **Thumb-zone friendly**: Primary actions accessible in lower 60% of screen
5. **Keyboard-aware**: Smart positioning to avoid keyboard overlap

### Visual Hierarchy
1. **Progressive disclosure**: Show only relevant information at each step
2. **Clear affordances**: Interactive elements look clickable/tappable
3. **Immediate feedback**: Every interaction produces instant visual response
4. **Consistent spacing**: 8px base unit, 16px between major sections
5. **Accessible contrast**: WCAG AA compliance minimum for all text

### Technical Principles
1. **Use the platform**: Leverage native HTML5 autocomplete patterns
2. **JavaScript required**: Autocomplete and dynamic chapter grid require JavaScript - no degraded fallback initially
3. **Performance budgets**: 200ms initial load, 50ms interaction response
4. **Clean separation**: Presentation (Blade), behavior (Alpine.js), validation (Laravel)
5. **Simple first, polish later**: Launch with essential functionality, add advanced animations/interactions based on user feedback
6. **Dark mode support**: All components SHALL support dark mode using Tailwind's `dark:` variants, following existing application patterns for consistent theming across light and dark modes

## Success Metrics

### User Experience
- Reduce average form completion time by 60%+ compared to current implementation
- Increase mobile form completion rate (reduce abandonment)
- Eliminate user confusion about valid chapter ranges (zero support requests)
- Maintain or improve accessibility score (WCAG AA compliance)

### Technical Performance
- Initial page load under 200ms
- Autocomplete filtering instant (<1ms client-side)
- Chapter grid rendering under 100ms
- Simple CSS transitions only (fade, opacity) - no complex animations initially
- Form submission under 500ms
- Zero JavaScript errors in production

### Business Impact
- Increase daily reading log entries per active user
- Improve user session duration and engagement
- Reduce support requests about form usage
- Enhance overall app satisfaction scores

## Implementation Notes

### Technology Stack
- **Backend**: Laravel 12 with existing ReadingFormService, BibleReferenceService
- **Frontend**: Alpine.js for state management, HTMX for form submission
- **Styling**: Tailwind CSS 4.0 with mobile-first responsive design
- **Bible Data**: Static configuration from config/bible.php (no API calls needed)

### Backend Compatibility
- **ReadingLogService** already supports non-contiguous chapter arrays via `data['chapters']` parameter
- Current service creates separate reading log entries for each chapter in the array
- **Note:** `formatPassageText()` method currently assumes contiguous ranges and needs minor enhancement to intelligently format non-contiguous selections (e.g., "Genesis 1, 3, 5" vs "Genesis 1-5")
- Enhancement tracked separately - not blocking for MVP (service will work, passage text may show as range even for non-contiguous selections)

### Localization
- **MVP Scope:** English-only for initial launch
- Bible book names use existing localized translations from `lang/en/bible.php`
- Form labels, buttons, and messages will be hardcoded in English for MVP
- **Post-MVP:** French localization can be added by extracting strings to Laravel's translation system (`__()` helper with keys in `lang/{locale}/forms.php`)
- **Rationale:** Launching English-only allows faster MVP delivery; localization architecture remains straightforward to add later

### Database Optimization

**Required:**
- Remove duplicate index `idx_user_date_read_calendar` from `reading_logs` table (exact duplicate of `idx_user_date`)
- Add composite index `idx_recent_books` on `(user_id, book_id, date_read)` to optimize Recent Books query (Requirement 2)

**Rationale:**
- Duplicate removal: Immediate performance benefit (faster writes, cleaner schema)
- Recent books index: Proactive optimization as user base grows (currently 24 users, beneficial at 100+ users with 500+ logs each)
- Additional benefits: Also optimizes book-specific reading history queries and book progress tracking

**Implementation details in design.md**

### Key Components
1. **BookAutocomplete.blade.php**: Searchable book selection with recent books
2. **ChapterGrid.blade.php**: Dynamic visual chapter selector with Quick Jump navigation for large books (>30 chapters)
3. **ReadingFormService**: Enhanced with getRecentBooks() and chapter suggestion logic
4. **Alpine.js Components** (following extracted function pattern per CLAUDE.md):
   - `bookSelector(allBooks, recentBooks)` - Handles book autocomplete state and filtering
   - `chapterGridSelector(totalChapters)` - Manages chapter selection state and Quick Jump scroll-to functionality
   - Components use extracted functions with `<script>` tags, not inline `x-data` (see design.md for detailed implementation)

### Development Phases
1. **Phase 1 (Day 1)**: Book autocomplete with recent books and search
2. **Phase 2 (Day 1-2)**: Chapter grid with single/multi-selection + Quick Jump navigation + scroll indicators
3. **Phase 3 (Day 2)**: Integration, testing, mobile optimization
4. **Phase 4 (Day 2-3)**: Accessibility, validation, essential transitions only
5. **Phase 5 (Post-MVP)**: Controller refactoring (Requirement 19) - simplify `store()` method to align with HTMX-native pattern

**Deferred for Post-Launch Polish:**
- Complex animations (staggered effects, micro-interactions, scale pulses)
- Confetti celebrations
- Animated progress bars
- Advanced visual effects

**Total Estimated Effort (MVP)**: 10-14 hours (1.5-2 development days)
**Total Estimated Effort (With Controller Refactoring)**: 12-16 hours (2-2.5 development days)
**Total Estimated Effort (With Polish)**: 16-20 hours (2.5-3 development days)
