# Reading Log V2 - Design

## 1. Design Principles

- **Mobile-First:** The design is optimized for small screens and touch interaction, then gracefully scales up to desktop.
- **Progressive Disclosure:** The user is only shown what they need at each step, reducing cognitive load.
- **Visual & Interactive:** The design favors visual elements like cards and grids over traditional form inputs to create a more engaging, app-like experience.

## 2. Component Breakdown

### 2.1. Main View (Container)

- This is the main container that will manage the state between the two steps.
- It will use an Alpine.js component to manage the currently selected book, date, and which step is active.
- Transitions between Step 1 and Step 2 will be animated (e.g., a horizontal slide or a fade).

### 2.2. Step 1: Select Your Reading

- **Layout (Mobile):** A single column, clean and focused.
  - `[Radio Group: Today / Yesterday]`
  - `[Large Tappable Card: "Tap to Select a Bible Book"]`
- **Layout (Desktop):** A two-column layout.
  - Left Column: Contains the date selection and the book selection card.
  - Right Column: Displays the user's current streak information to provide encouragement.
- **Book Selection Card:** A large, visually distinct card with a clear call to action. It is not an input field.

### 2.3. Book Selection Modal

- **Trigger:** Tapping the "Select a Bible Book" card.
- **Layout (Mobile):** A full-screen modal that slides up from the bottom.
  - `[Header with Search Input and Close Button]`
  - `[Tab Navigation: Recent / Old Testament / New Testament]`
  - `[Scrollable Content Area for book list/grid]`
- **Layout (Desktop):** A centered modal dialog with a backdrop.
  - `max-width: 640px`.
  - Same inner layout as mobile (Search, Tabs, Content).
- **Content:** Books are displayed in a grid of cards with their full name for easy tapping.

### 2.4. Step 2: Log Your Chapters & Notes

- **Trigger:** Successfully selecting a book from the modal.
- **Layout:** A single view for both mobile and desktop.
  - `[Header displaying selected book name, e.g., "Genesis"]`
  - `[Visual Chapter Grid]`
  - `[Textarea for Notes]`
  - `[Submit Button: "Log Reading"]`

### 2.5. Chapter Grid

- **Functionality:** The core of the Step 2 design.
- **Display:** A grid of tappable circles or squares, each representing a chapter.
- **State:** The component will know the total number of chapters for the selected book.
- **Interaction:**
  - **Single Chapter:** Tap a number. It becomes selected.
  - **Chapter Range:** Tap a start number, then tap an end number. All chapters in between are selected.
  - **Deselection:** Tapping a selected chapter deselects it. If a range is selected, tapping any chapter in the range deselects the whole range.
- **Styling:**
  - `Default`: Gray, outlined circle.
  - `Selected`: Primary color, filled circle.
  - `Already Read`: A different style (e.g., lighter primary color, or a checkmark icon) to show progress. This data will need to be fetched from the backend.

## 3. User Flow

1.  User lands on the dashboard. The Step 1 view is displayed.
2.  User selects a date.
3.  User taps "Select a Bible Book".
4.  The Book Selection Modal opens.
5.  User searches for and/or taps a book.
6.  The modal closes. The selected book's data is passed to the main view component.
7.  The main view transitions from Step 1 to Step 2.
8.  The Chapter Grid for the selected book is displayed.
9.  User taps chapter(s).
10. User optionally fills in notes.
11. User clicks "Log Reading".
12. The form is submitted via HTMX. On success, the component can either reset to Step 1 or display a success message.
