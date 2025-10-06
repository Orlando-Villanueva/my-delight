# Reading Log V2 - Tasks

This checklist breaks down the implementation of the V2 reading log form based on the requirements and design specifications.

## Phase 1: Backend & Core Structure

- [ ] Create a new route/controller method to serve the initial V2 form view.
- [ ] Create a new Blade file for the main container of the two-step form (`reading-log-v2.blade.php`).
- [ ] Create the basic Alpine.js component structure in the main Blade file to manage state (currentStep, selectedBook, selectedDate).
- [ ] Create a new Blade partial for Step 1 (`_step1-book-select.blade.php`).
- [ ] Create a new Blade partial for Step 2 (`_step2-chapter-log.blade.php`).
- [ ] Create a new Blade partial for the Book Selection Modal (`_modal-book-select.blade.php`).
- [ ] Update the backend `BookProgressService` or create a new method to fetch which chapters of a given book a user has already read.

## Phase 2: Book Selection Modal

- [ ] Implement the frontend for the Book Selection Modal (HTML/CSS).
- [ ] Implement the Alpine.js logic for the modal:
  - [ ] Opening/closing the modal.
  - [ ] Search/filter functionality.
  - [ ] Tabbed navigation (Recent, OT, NT).
  - [ ] Handling book selection and dispatching the `book-selected` event.
- [ ] Ensure the modal is responsive (full-screen on mobile, centered on desktop).

## Phase 3: Chapter Grid

- [ ] Implement the frontend for the Chapter Grid component.
- [ ] Create a dedicated Alpine.js component for the Chapter Grid.
- [ ] The component should accept the selected book (including total chapters and list of read chapters) as a prop.
- [ ] Implement the chapter selection logic:
  - [ ] Single chapter selection.
  - [ ] Range selection (start/end tap).
  - [ ] Deselection.
- [ ] Implement the different visual states for chapters (default, selected, already read).
- [ ] Ensure the grid is responsive and wraps correctly on different screen sizes.

## Phase 4: Integration & Form Submission

- [ ] Wire the main component to listen for the `book-selected` event from the modal and transition from Step 1 to Step 2.
- [ ] Create a hidden input in the form that is populated with the selected chapter(s) from the Chapter Grid component.
- [ ] Update the `ReadingLogController@store` method to handle the new chapter input format (e.g., a comma-separated string "5,6,7" or a JSON array).
- [ ] Ensure the form submission is handled via HTMX.
- [ ] Implement success and error handling for the form submission.

## Phase 5: Polishing

- [ ] Add smooth animated transitions between Step 1 and Step 2.
- [ ] Add animations for the modal appearing/disappearing.
- [ ] Review and refine all styling on both mobile and desktop.
- [ ] Write Pest tests for the new backend logic (e.g., fetching read chapters, storing new log format).
