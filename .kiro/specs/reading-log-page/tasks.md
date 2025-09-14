# Implementation Plan - Streamlined

## Priority 1: Core Conversion (MVP)

- [ ] 1. Create reading log page route and controller
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
  - Use Laravel's `old()` helper for validation error persistence
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 6.3, 8.2_

- [ ] 4. Update form submission with simplified success handling
  - Modify `ReadingLogController@store` method to handle page context
  - Return reading log page content with Laravel flash message instead of modal response
  - Ensure form is reset after successful submission
  - Maintain existing dashboard update triggers via HTMX
  - _Requirements: 4.1, 4.2, 4.3, 4.4_

- [ ] 5. Add simplified success message display
  - Add success message section at top of page content partial
  - Use Laravel's `session('success')` with auto-dismiss after 5 seconds
  - Include manual close button for immediate dismissal
  - _Requirements: 4.1, 4.5_

- [ ] 6. Update navigation components for page routing
  - **Desktop sidebar**: Add third navigation item between History and user profile
  - **Mobile FAB**: Update to use HTMX page navigation, keep visible across all pages
  - **Header button**: Replace modal trigger with HTMX page navigation
  - Use same HTMX attributes: `hx-get="{{ route('logs.create') }}"`, `hx-target="#page-container"`, `hx-push-url="true"`
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 2.2, 2.4, 5.1, 5.2, 7.1, 7.2_

- [ ] 7. Update Alpine.js state management
  - Extend currentView state to handle 'create' value
  - Update title update function for "Log Reading" page
  - Ensure navigation highlighting works for all three pages
  - _Requirements: 1.3, 1.4_

- [ ] 8. Remove modal implementation
  - Remove modal backdrop and slide-over panel from authenticated layout
  - Remove modal-related Alpine.js state (modalOpen) and handlers
  - Remove "Cancel" button from form (no longer needed)
  - Clean up modal-specific CSS and unused attributes
  - _Requirements: 5.2_

- [ ] 9. Test core functionality
  - Test all navigation flows (desktop sidebar, mobile FAB, header button)
  - Verify form submission, success message, and dashboard updates
  - Test browser back/forward navigation and direct URL access
  - Test form validation and error handling with `old()` helper
  - Verify responsive behavior across device sizes
  - _Requirements: 1.1-1.4, 2.1-2.4, 4.1-4.5, 5.1-5.4, 6.1-6.4, 7.1-7.4_

## Priority 2: Future Enhancements (Post-MVP)

- [ ] 10. Advanced draft persistence (if user testing shows need)
  - Implement Alpine.js `$persist` with sessionStorage
  - Add draft restoration and clearing logic
  - Test draft persistence across page navigation
  - _Requirements: 8.1, 8.4, 8.5_

## Implementation Notes

### Simplified Approach Benefits
- **Faster delivery**: Core conversion completed in 9 focused tasks
- **Less complexity**: Uses standard Laravel patterns (flash messages, `old()` helper)
- **Better maintainability**: Fewer custom JavaScript solutions
- **Standard UX**: Familiar Laravel form handling patterns

### Key Simplifications Applied
1. **Success Messages**: Laravel flash messages instead of complex dismissal logic
2. **Form State**: Native browser + `old()` helper instead of sessionStorage drafts
3. **Mobile FAB**: Consistent visibility instead of conditional hiding
4. **Header Button**: Standard navigation instead of special refresh behavior
5. **Task Structure**: Combined modal cleanup, prioritized core conversion

### Testing Focus
- Prioritize happy path user flows
- Ensure feature parity with existing modal
- Verify HTMX integration works seamlessly
- Test responsive behavior on all devices