# Reading Log V2 - Requirements

## 1. Overview

The goal is to redesign the reading log form to be more intuitive, interactive, and mobile-first. The new design will replace the traditional, single-page form with a guided, two-step process.

## 2. Core User Stories

- As a user, I want to log my daily Bible reading quickly and easily on my mobile device.
- As a user, I want a visual and error-proof way to select the book and chapters I have read.
- As a user, I want the interface to feel modern, polished, and app-like.
- As a user, I want to be able to add optional notes to my reading log entry.

## 3. Functional Requirements

### Step 1: Book & Date Selection
- The user must be able to select either "Today" or "Yesterday" as the reading date.
- The UI must present a clear, tappable element to initiate book selection.
- Tapping the book selection element must open a modal dialog.
  - On mobile, this modal should be full-screen.
  - On desktop, this modal should be centered with a backdrop.
- The book selection modal must allow searching for a book by name or abbreviation.
- The book selection modal should show a list of recently read books for quick access.
- The book selection modal should group books by Old and New Testament.
- Selecting a book from the modal must close the modal and advance the user to Step 2.

### Step 2: Chapter & Notes
- The UI must clearly display the book selected in Step 1.
- The user must be able to select the chapter(s) they read.
  - This will be done via a visual grid of chapter numbers for the selected book.
  - The user must be able to select a single chapter by tapping.
  - The user must be able to select a range of chapters (e.g., by tapping a start and end chapter).
- The system must prevent the user from logging chapters that do not exist for the selected book.
- The user must be able to add optional, multi-line text notes.
- The user must be able to submit the form to log their reading.

## 4. Non-Functional Requirements

- The entire flow must be fully responsive and optimized for both mobile and desktop screens.
- The interface should use smooth transitions and animations to feel fluid and interactive.
- All business logic must remain in the service layer, according to existing project conventions.
