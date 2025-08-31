# Implementation Plan

- [ ] 1. Create reading log page route and controller method
  - Add GET route for `/logs/create` in web.php with same auth middleware as dashboard/history
  - Create `create()` method in `ReadingLogController` that returns the page view
  - Ensure method provides same data as existing modal (books, allowYesterday, etc.)
  - _Requirements: 1.2, 3.1, 6.1, 7.3_

- [ ] 2. Create main reading log page template
  - Create `resources/views/logs/create.blade.php` extending authenticated layout
  - Set page title to "Log Reading" and subtitle to "Record your Bible reading progress"
  - Include main content div with proper HTMX attributes for updates
  - _Requirements: 1.4, 6.1, 6.2_

- [ ] 3. Create reading log page content partial
  - Create `resources/views/partials/reading-log-page-content.blade.php`
  - Copy form content from existing `reading-log-form.blade.php`
  - Remove modal-specific elements (close button, modal title ID, modal description)
  - Update form HTMX target to `#page-container` instead of modal content
  - Add Alpine.js draft persistence with $persist using sessionStorage
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 6.3, 8.1, 8.2, 8.3_

- [ ] 4. Add success message handling to reading log page
  - Add dismissable success message display section at top of page content partial
  - Use existing error message styling pattern for consistency
  - Include close button with Alpine.js click handler for dismissal
  - Include reading details in success message text
  - Ensure success message appears after form submission
  - _Requirements: 4.1, 4.3, 4.5_

- [ ] 5. Update form submission to stay on page with success message
  - Modify `ReadingLogController@store` method to handle page context
  - Return reading log page content with success message instead of modal response
  - Ensure form is reset after successful submission
  - Clear draft data after successful submission
  - Maintain existing dashboard update triggers via HTMX
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.6, 8.4_

- [ ] 6. Add desktop sidebar navigation item for Log Reading
  - Edit `resources/views/layouts/authenticated.blade.php`
  - Add third navigation button in desktop sidebar between History and user profile
  - Use HTMX attributes: `hx-get="{{ route('logs.create') }}"`, `hx-target="#page-container"`, `hx-push-url="true"`
  - Add Alpine.js click handler to update currentView state to 'create'
  - Apply active styling when currentView equals 'create'
  - _Requirements: 1.1, 1.2, 1.3, 7.1, 7.2_

- [ ] 7. Update mobile floating action button to use page navigation
  - Modify floating action button in authenticated layout
  - Replace modal trigger attributes with HTMX page navigation
  - Use same HTMX attributes as desktop navigation with `hx-push-url="true"`
  - Add Alpine.js click handler to update currentView to 'create'
  - _Requirements: 2.1, 2.2, 2.4, 7.1, 7.2_

- [ ] 8. Update desktop header Log Reading button to use page navigation
  - Modify header "Log Reading" button in authenticated layout
  - Replace modal trigger with HTMX page navigation attributes including `hx-push-url="true"`
  - Remove modal-related Alpine.js click handler
  - Add currentView update to 'create'
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 7.1, 7.2_

- [ ] 9. Update Alpine.js state management for new navigation option
  - Extend currentView state in authenticated layout to handle 'create' value
  - Update title update function to handle "Log Reading" page title
  - Ensure navigation highlighting works for all three pages (dashboard, logs, create)
  - _Requirements: 1.3, 1.4_

- [ ] 10. Remove modal-related code and styling
  - Remove modal backdrop and slide-over panel from authenticated layout
  - Remove modal-related Alpine.js state (modalOpen)
  - Remove modal-specific CSS and HTMX indicator styles
  - Clean up any unused modal-related attributes
  - _Requirements: 5.2_

- [ ] 11. Update form action buttons for page context
  - Remove "Cancel" button from form (no longer needed without modal)
  - Update "Log Reading" submit button styling for page context
  - Ensure HTMX loading indicators work properly for page submission
  - _Requirements: 3.5, 6.3_

- [ ] 12. Implement draft persistence functionality
  - Add Alpine.js $persist configuration to form data in reading log page content
  - Implement draft data restoration when returning to the page
  - Add clearDraft() method to be called after successful form submission
  - Ensure draft data is cleared on page reload (browser refresh)
  - Test draft persistence across page navigation
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ] 13. Test and verify all navigation flows work correctly
  - Test desktop sidebar navigation to reading log page
  - Test mobile floating action button navigation
  - Test header button navigation
  - Verify form submission, success message, and dashboard updates
  - Test browser back/forward navigation and direct URL access
  - Test draft persistence and clearing functionality
  - Test responsive behavior and ensure no broken functionality
  - _Requirements: 1.1, 1.2, 2.1, 2.2, 4.1, 4.4, 5.1, 6.4, 7.1, 7.2, 7.3, 7.4, 8.1-8.5_