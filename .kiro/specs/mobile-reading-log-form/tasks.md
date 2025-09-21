# Implementation Plan

## Technology Boundaries

**Alpine.js for Form UI Logic**: Grid interactions, selection states, visual feedback, progressive disclosure
**HTMX for Server Communication**: Form submission, cross-section updates, server validation, testament persistence
**Progressive Enhancement**: Standard form fallback without JavaScript

- [x] 1. Create grid book selector component

  - Create `resources/views/components/bible/grid-book-selector.blade.php` with testament toggle integration
  - Implement search input with real-time filtering functionality
  - Build responsive book grid (2-col mobile, 3-4 col desktop) with touch-friendly buttons
  - Add Alpine.js `gridBookSelector()` function for state management
  - Include book selection event dispatching and hidden form input
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 2.1, 2.2, 2.3, 2.4, 2.5_

- [x] 2. Create grid chapter selector component

  - Create `resources/views/components/bible/grid-chapter-selector.blade.php` with numbered chapter grid
  - Implement Alpine.js `gridChapterSelector()` function with click logic (single/range/unselect)
  - Add chapter range creation for sequential selections with visual feedback
  - Build responsive chapter grid (4-6 columns) with proper button states
  - Include back navigation button and chapter selection event dispatching
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 4.1, 4.2, 4.3, 4.4, 4.5, 4.6_

- [x] 3. Create selection label display component (HTMX-Alpine.js First)

  - Create `resources/views/components/bible/selection-label.blade.php` as simple display component
  - Use Alpine.js `x-bind` for reactive updates from parent state (no internal state management)
  - Implement label updates: "Selection: Genesis" → "Selection: Genesis 1" → "Selection: Genesis 1-5"
  - Add proper styling with existing theme colors and responsive design
  - Include empty state handling with `x-if` templates
  - Follow HTMX-Alpine.js first principle: minimal client-side state, server-driven data
  - _Requirements: 1.5, 3.4, 4.3_

- [x] 4. Add CSS styles for grid components (Tailwind-native approach)

  - Use inline Tailwind utilities for component styling instead of custom CSS classes
  - Add only essential animations to app.css (`.animate-select-pulse`, `.animate-range-pulse`, `.chapter-range-shadow`)
  - Implement hover states with Tailwind utilities (`hover:-translate-y-px`, `hover:border-primary-500/30`)
  - Use responsive grid utilities directly (`grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3`)
  - Include touch target compliance with inline styles (`style="min-height: 44px"`) and dark mode support
  - Alpine.js functions return full Tailwind utility strings for better maintainability
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 8.1, 8.2, 8.5, 8.6_

- [x] 5. Create main grid reading form component

  - Create `resources/views/components/bible/grid-reading-form.blade.php` as main form wrapper
  - Integrate existing date selection, notes textarea, and submit button components
  - Add Alpine.js form controller with book/chapter state management
  - Implement progressive disclosure: books → chapters → submit enabled
  - Include form event handling for book selection, chapter selection, and back navigation
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 7.1, 7.2, 7.3, 7.4, 7.5_

- [x] 6. Add user guidance and instruction text

  - Add "💡 Tip: Click a chapter, then click another to create a range (e.g., 3-7)" to chapter selector
  - Implement "No books found" message for empty search results
  - Add loading states and error handling for component failures
  - Include proper ARIA labels and accessibility attributes for screen readers
  - Add keyboard navigation support for all interactive elements
  - _Requirements: 8.3, 8.4, 8.5_

- [ ] 7. Update reading log form partial to use grid components

  - Modify `resources/views/partials/reading-log-form.blade.php` to integrate grid components
  - Replace existing book dropdown and chapter text input with grid selectors
  - Maintain all existing form validation and error handling
  - Preserve HTMX form submission and success message functionality
  - Ensure backward compatibility with existing form processing logic
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [ ] 8. Add comprehensive component tests

  - Write unit tests for `gridBookSelector()` Alpine.js function (filtering, selection, events)
  - Write unit tests for `gridChapterSelector()` function (single, range, unselect logic)
  - Create feature tests for form submission with grid component data
  - Test responsive design across mobile, tablet, and desktop breakpoints
  - Validate accessibility compliance with screen reader and keyboard navigation testing
  - _Requirements: All requirements validation_

- [ ] 9. Create development demo page

  - Create temporary route `/grid-form-demo` for component testing
  - Build demo page showing grid selectors in isolation with different book counts
  - Include examples of all interaction states: default, selected, hover, range selection
  - Add debug output showing current selection state and form data
  - Include responsive testing viewport with mobile/desktop toggle
  - _Requirements: Visual validation and development testing_

- [ ] 10. Integration testing and polish

  - Test complete user flow: testament selection → book grid → chapter grid → form submission
  - Verify form validation errors display correctly with grid components
  - Test HTMX form submission and success state handling
  - Validate theme color consistency with existing app design
  - Perform cross-browser testing (Chrome, Firefox, Safari, Edge) and mobile device testing
  - _Requirements: All requirements integration testing_

- [ ] 11. Performance optimization and final cleanup

  - Optimize Alpine.js performance with proper reactivity and minimal re-renders
  - Add client-side caching for book data and search filtering
  - Minimize CSS bundle size and ensure efficient grid layouts
  - Remove temporary demo routes and development-only code
  - Update documentation with component usage examples and integration patterns
  - _Requirements: Performance and maintainability_