# Bible Reading Habit Builder - UI Requirements Document (MVP)

## Overview

This document outlines the specific UI requirements for the Bible Reading Habit Builder MVP. It serves as a guide for designers and developers implementing the frontend of the application, ensuring a cohesive, intuitive, and motivating user experience that aligns with the core value proposition.

## Core Design Principles

1. **Simplicity**: Prioritize clean, uncluttered interfaces that focus user attention on key actions and progress indicators.
2. **Motivation-Driven**: Design elements should reinforce the user's progress and encourage continued engagement.
3. **Responsive Design**: Ensure consistent functionality and visual appeal across all device sizes.
4. **Accessibility**: Follow WCAG 2.1 AA standards to ensure the application is usable by people with various abilities.
5. **Bilingual Support**: Design must accommodate both English and French language interfaces seamlessly.

## Brand Identity

### Color Palette

- **Primary**: `#3366CC` (Blue) - Used for primary actions, streak indicators, and active states
- **Secondary**: `#66CC99` (Green) - Used for completion indicators and success states
- **Accent**: `#FF9933` (Orange) - Used for highlighting important information and calls to action
- **Neutral Light**: `#F5F7FA` - Background colors, light mode
- **Neutral Mid**: `#D1D7E0` - Borders, dividers, inactive states
- **Neutral Dark**: `#4A5568` - Primary text color
- **Error**: `#E53E3E` - Error states and alerts

### Typography

- **Primary Font**: Inter (sans-serif)
- **Heading Sizes**:
  - H1: 24px (mobile), 32px (desktop)
  - H2: 20px (mobile), 24px (desktop)
  - H3: 18px (mobile), 20px (desktop)
  - H4: 16px (mobile), 18px (desktop)
- **Body Text**: 16px
- **Small Text/Captions**: 14px
- **Line Height**: 1.5 for optimal readability

### Iconography

- Use a consistent icon set throughout the application (recommended: Phosphor Icons)
- Key icons needed:
  - Bible/Book icon (for reading logs)
  - Flame/Fire icon (for streaks)
  - Calendar icon (for history view)
  - Chart/Statistics icon (for stats view)
  - Checkmark icon (for completed items)
  - Language toggle icon

## Layout and Navigation

### Mobile Layout

- **Navigation**: Bottom tab bar with 3 primary destinations:
  - Dashboard
  - History
  - Profile/Settings
- **Content Area**: Single column layout with card-based components
- **Floating Action Button**: Primary action button for "Log Reading" positioned bottom-right, always visible across all screens for easy daily access

### Tablet/Desktop Layout

- **Navigation**: Left sidebar with always-visible navigation (256px width)
- **Content Area**: Two-column layout on larger screens
  - Primary content (70%)
  - Supporting statistics/information (30%)
- **Dashboard Layout**: Grid-based layout with resizable cards for different statistics
- **Primary Action**: Prominent "Log Reading" button in desktop header for immediate access, replacing the floating action button pattern on larger screens

### Hybrid Action Button Approach

The primary "Log Reading" action uses a **responsive hybrid approach** optimized for each platform:

#### Mobile (< 1024px)
- **Floating Action Button (FAB)**: Traditional mobile pattern
- **Position**: Bottom-right, above bottom navigation
- **Size**: 56px circular button with icon
- **Behavior**: Always visible, follows mobile UX conventions

#### Desktop (≥ 1024px)
- **Header Action Button**: Prominent button in page header
- **Position**: Top-right of content area header
- **Size**: Full-sized button with icon and text label
- **Features**: 
  - Quick streak indicator on extra-large screens
  - Better visual hierarchy and accessibility
  - Contextual placement near page content

This approach ensures platform-appropriate interactions while maintaining consistent functionality across all devices.

### Common UI Patterns

- **Cards**: Used for discrete chunks of information (streak card, statistics card)
- **Lists**: Used for reading logs and history items
- **Grids**: Used for Book Completion Grid and calendar visualization
- **Progress Indicators**: Used throughout to show completion status

## Key Screen Requirements

### 1. Authentication Screens

#### Login Screen

- **Required Elements**:
  - Application logo
  - Email input field
  - Password input field with visibility toggle
  - "Remember me" checkbox
  - "Login" primary button (submits to Fortify's `/login` endpoint)
  - "Forgot password?" link
  - "Register" secondary link
  - Language toggle (EN/FR)
  - HTMX integration for seamless form submission and error handling
  - Server-side validation error display

#### Registration Screen

- **Required Elements**:
  - Application logo
  - Name input field
  - Email input field
  - Password input field with visibility toggle
  - Password confirmation field
  - Password strength indicator
  - Terms of service checkbox
  - "Register" primary button (submits to Fortify's `/register` endpoint)
  - "Login" secondary link
  - Language toggle (EN/FR)
  - HTMX integration for seamless form submission and error handling
  - Server-side validation error display from Fortify backend

### 2. Dashboard Screen

- **Required Components**:
  - **Streak Card** (prominently displayed):
    - Current streak counter with flame icon
    - Visual emphasis (size, color)
    - Longest streak ever display
  - **Calendar Visualization**:
    - Month view similar to GitHub contribution graph
    - Color-coded squares indicating reading activity
    - Current date highlighted
  - **Summary Statistics**:
    - Total chapters read
    - Books started vs. completed
    - First and most recent reading dates
  - **Book Completion Grid**:
    - All 66 Bible books displayed in canonical order
    - Color-coding by completion status:
      - Not started: Light gray
      - In progress: Blue
      - Completed: Green

### 3. Reading Log Screen

- **Required Elements**:
  - Date selector (defaults to current date)
  - Two-step Bible passage selector:
    - Book dropdown (all 66 books)
    - Chapter dropdown (dynamically updates based on selected book)
  - Optional notes field (with character counter)
  - "Save" primary button
  - "Cancel" secondary button
  - Success confirmation after saving

### 4. History Screen

- **Required Elements**:
  - Calendar view for date selection
  - Filterable list of reading logs
  - Each log entry displays:
    - Date
    - Bible reference (Book + Chapter)
    - Preview of notes (if any)
    - Option to expand/collapse notes
  - Option to filter by book, date range

### 5. Statistics Screen

- **Required Components**:
  - **Bible Reading Progress**:
    - Overall completion percentage
    - Visual indicator of progress through the Bible
  - **Book Completion Grid**:
    - Books arranged by canonical order
    - Color-coding for completion status
    - Progress percentage for each book
  - **Reading History Visualization**:
    - Calendar view with color-coded days
    - Legend explaining the color coding

### 6. Profile/Settings Screen

- **Required Elements**:
  - User information display
  - Language selection (English/French)
  - Account settings options
  - Logout button

## Component Specifications

### 1. Streak Counter Component

- **Visual Design**:
  - Large, prominent display
  - Current streak number with flame icon
  - Longest streak shown below in smaller text
  - Animation when streak increases

### 2. Book Completion Grid Component

- **Layout**:
  - Grid/table layout with books in canonical order
  - Old Testament and New Testament grouping
  - Each book shows:
    - Book name
    - Completion percentage
    - Visual color indicator
  - Should accommodate both English and French book names

### 3. Calendar Visualization Component

- **Visual Design**:
  - Month-based view similar to GitHub contribution grid
  - 7 columns (days of week)
  - Color intensity based on number of chapters read
  - Legend explaining the color coding
  - Interactive squares with hover/tap state

### 4. Bible Passage Selector Component

- **Behavior**:
  - Two-step selection process
  - Step 1: Select book from dropdown (all 66 Bible books)
  - Step 2: Dynamic chapter dropdown (based on selected book)
  - Validation to ensure selections are valid
  - Clear visual feedback on selection

### 5. Reading Log Entry Component

- **Layout**:
  - Card-based design
  - Date display
  - Bible reference (Book + Chapter)
  - Expandable notes section
  - Visual indicator for days that extend streak

## Responsive Design Requirements

### Mobile Breakpoints

- **Small Mobile**: 320px - 375px
- **Standard Mobile**: 376px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px and above

### Mobile-Specific Adaptations

- Bottom navigation with 3 tabs instead of sidebar
- Single column layout
- Larger touch targets (min 44px × 44px) 
- Collapsible sections for statistics
- Simplified Bible progress visualization
- Floating Action Button positioned above bottom navigation for primary "Log Reading" action

### Desktop Enhancements

- Multi-column dashboard layout
- Expanded statistics visualizations
- Always-visible sidebar navigation with user profile section
- Hover states for interactive elements
- Keyboard shortcuts for common actions
- **Header Action Button**: Prominent "Log Reading" button in page header with:
  - Icon and text label for clarity
  - Quick streak indicator on extra-large screens
  - Better accessibility and visual hierarchy
  - Contextual placement near page content

## Language Support Requirements

- All UI elements must support both English and French
- Language toggle must be easily accessible from all screens
- Text elements must accommodate ~30% expansion for French translations
- Date formats must adapt to language preferences
- Bible book names must be displayed in the selected language

## Accessibility Requirements

- Color contrast ratio of at least 4.5:1 for normal text
- Focus indicators for keyboard navigation
- Alt text for all images and icons
- ARIA labels for interactive elements
- Screen reader compatibility
- Support for text resizing up to 200%
- Touch targets minimum size of 44px × 44px

## Performance Requirements

- First Contentful Paint (FCP) under 1.5 seconds
- Time to Interactive (TTI) under 3.5 seconds
- Optimize image assets for web delivery
- Lazy loading for off-screen content
- Implement appropriate loading indicators for HTMX requests

## Implementation Details

### Technology Stack

- **Frontend Framework**: HTMX + Alpine.js
- **CSS Framework**: Tailwind CSS
- **Icon Library**: Phosphor Icons
- **Localization**: Laravel's built-in localization system

### HTMX Integration

- Use `hx-get` and `hx-post` for dynamic content updates
- Implement `hx-swap` for smooth transitions
- Use `hx-target` to update specific page sections
- Add appropriate loading indicators

### Alpine.js Usage

- Use for small interactive components (dropdowns, toggles)
- Manage form validation states
- Handle responsive menu behavior
- Implement simple animations

## Validation & Quality Assurance

- Cross-browser testing (Chrome, Firefox, Safari, Edge)
- Mobile device testing on iOS and Android
- WCAG 2.1 AA compliance validation
- Performance testing using Lighthouse
- Usability testing with representative users

## Appendix: Feature Prioritization

### Must-Have UI Elements
- Authentication screens
- Reading log input
- Current streak display
- Basic calendar visualization

### High-Priority UI Elements
- Book completion grid
- Summary statistics display
- Mobile responsive layout
- French language support

### Medium-Priority UI Elements (Implement if time permits)
- Animations for streak increases
- Detailed reading history filters
- Interactive calendar with reading details
- Enhanced visual styling and polish
