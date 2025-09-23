# Requirements Document

## Introduction

This feature enhances the reading log form to provide a mobile-first UI/UX that reduces friction for Bible reading habit tracking. The current form uses a 66-book dropdown and text input for chapters, which creates unnecessary friction on mobile devices for the app's most critical feature. The goal is to replace these with touch-friendly, visual selection interfaces that make logging reading faster and more intuitive on both mobile and desktop devices, with mobile being the primary optimization target.

## Requirements

### Requirement 1

**User Story:** As a mobile user, I want to select Bible books through a visual grid interface instead of scrolling through a long dropdown, so that I can quickly find and select books without endless scrolling.

#### Acceptance Criteria

1. WHEN the user opens the reading log form THEN the system SHALL display a testament toggle with two options: "Old Testament", "New Testament" with "Old Testament" selected by default
2. WHEN the user selects a testament filter THEN the system SHALL display only books from that testament in a grid layout below the toggle
3. WHEN displaying books in the grid THEN the system SHALL show 2 columns on mobile and 3-4 columns on larger screens
4. WHEN displaying each book button THEN the system SHALL show the book name and chapter count (e.g., "Genesis - 50 chapters")
5. WHEN the user taps a book button THEN the system SHALL display a label under the grid showing "Book: [BookName]" AND replace the book grid with a chapter selection grid for that specific book

### Requirement 2

**User Story:** As a mobile user, I want to search for Bible books by name, so that I can quickly find specific books without browsing through the entire list.

#### Acceptance Criteria

1. WHEN the book selection interface is displayed THEN the system SHALL provide a search input field with placeholder text "Search books..."
2. WHEN the user types in the search field THEN the system SHALL filter the book grid in real-time to show only matching books
3. WHEN filtering books THEN the system SHALL match book names case-insensitively
4. WHEN no books match the search THEN the system SHALL display a "No books found" message
5. WHEN the search field is cleared THEN the system SHALL restore the full book list based on the selected testament filter

### Requirement 3

**User Story:** As a mobile user, I want to select chapters through a visual grid interface instead of typing numbers, so that I can avoid mobile keyboard input and potential typing errors.

#### Acceptance Criteria

1. WHEN a book is selected THEN the system SHALL replace the book grid with a grid of numbered chapter buttons specific to that book
2. WHEN displaying chapter buttons THEN the system SHALL arrange them in 4-6 columns based on screen width
3. WHEN displaying chapter buttons THEN the system SHALL number them from 1 to the book's total chapter count
4. WHEN the user taps a chapter button THEN the system SHALL select that chapter with visual feedback AND update the label to "Book: [BookName] [ChapterNumber]"
5. WHEN chapters are selected THEN the system SHALL enable the submit button to allow logging the reading

### Requirement 4

**User Story:** As a user who reads multiple chapters, I want to select chapter ranges through a simple click interface, so that I can log sequential chapters without typing complex range syntax.

#### Acceptance Criteria

1. WHEN the user clicks a chapter button for the first time THEN the system SHALL select that single chapter and update the label to "Book: [BookName] [ChapterNumber]"
2. WHEN the user clicks the same chapter button again THEN the system SHALL unselect the chapter and clear the chapter selection from the label
3. WHEN the user clicks a different sequential chapter button after selecting one THEN the system SHALL create a range selection and update the label to "Book: [BookName] [StartChapter]-[EndChapter]"
4. WHEN a range is selected THEN the system SHALL visually highlight all chapters within the range (including start, middle, and end chapters)
5. WHEN the user clicks any chapter button after a range is selected THEN the system SHALL reset to a new single chapter selection
6. WHEN the user clicks a non-sequential chapter after selecting one THEN the system SHALL reset to the new single chapter selection

### Requirement 5

**User Story:** As a user, I want the new interface to maintain all existing functionality, so that no features are lost in the mobile optimization.

#### Acceptance Criteria

1. WHEN using the new interface THEN the system SHALL maintain compatibility with all existing validation rules
2. WHEN form errors occur THEN the system SHALL display errors appropriately within the new interface
3. WHEN old form values exist (after validation errors) THEN the system SHALL restore the previous selections in the new interface
4. WHEN using HTMX form submission THEN the system SHALL work seamlessly with the new selection components
5. WHEN the form is submitted THEN the system SHALL submit the same data format as the current implementation

### Requirement 6

**User Story:** As a user on different devices, I want the mobile-first interface to work well on both mobile and desktop, so that I have a consistent and optimized experience across all my devices.

#### Acceptance Criteria

1. WHEN using on mobile devices THEN the system SHALL optimize touch targets to be at least 44px for easy tapping
2. WHEN using on desktop THEN the system SHALL provide appropriate hover states and keyboard navigation
3. WHEN using with assistive technologies THEN the system SHALL maintain proper ARIA labels and semantic markup
4. WHEN using in dark mode THEN the system SHALL follow existing dark mode styling conventions
5. WHEN the screen size changes THEN the system SHALL responsively adjust grid layouts and button sizes

### Requirement 7

**User Story:** As a user, I want to be able to navigate back and change my book selection, so that I can easily correct mistakes or explore different books without restarting the form.

#### Acceptance Criteria

1. WHEN in chapter selection mode THEN the system SHALL provide a clear way to go back to book selection
2. WHEN the user navigates back to book selection THEN the system SHALL restore the book grid and hide the chapter grid
3. WHEN navigating back THEN the system SHALL maintain the testament filter selection
4. WHEN navigating back THEN the system SHALL clear the book selection label
5. WHEN a new book is selected after navigating back THEN the system SHALL update to show chapters for the new book

### Requirement 8

**User Story:** As a user, I want clear visual feedback and intuitive interaction patterns, so that I understand the current state and available actions at all times.

#### Acceptance Criteria

1. WHEN displaying book and chapter buttons THEN the system SHALL use these visual states:
   - Default: Neutral (white/gray background)
   - Selected: Primary blue background with white text
   - Hover: Darker shade in light mode, lighter shade in dark mode
   - Range: All chapters in range show blue background
   - Disabled: Standard disabled styling
2. WHEN users interact with buttons THEN the system SHALL provide immediate visual feedback through color changes to blue (selected state)
3. WHEN search returns no results THEN the system SHALL display "No books found" message instead of an empty grid
4. WHEN displaying chapter selection interface THEN the system SHALL include instructional text: "💡 Tip: Click a chapter, then click another to create a range (e.g., 3-7)"
5. WHEN implementing buttons THEN the system SHALL support ARIA labels for screen readers and keyboard navigation
6. WHEN implementing focus states THEN the system SHALL NOT display focus indicators per design requirements