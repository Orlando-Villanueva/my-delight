# Bible Habit Builder - UI Requirements Document (MVP)

## Overview
This document outlines the user interface requirements for the Bible Habit Builder MVP. All features described here are **free tier features** that will be included in the initial launch. The focus is on creating a clean, motivating, and mobile-first experience that encourages consistent Bible reading habits.

## Design Principles

### Core Values
- **Simplicity First**: Clean, uncluttered interface that doesn't distract from the primary goal
- **Mobile-Optimized**: Designed primarily for mobile users who log readings throughout the day
- **Encouraging**: Visual design that celebrates progress and motivates consistency
- **Accessible**: Readable fonts, good contrast, intuitive navigation

### Visual Identity
- **Primary Color**: #3366CC (Professional Blue) - for primary actions and progress indicators
- **Success Color**: #66CC99 (Encouraging Green) - for completed items and positive feedback
- **Background**: Clean whites and light grays with subtle borders
- **Typography**: Clear, readable fonts with proper line-height for mobile reading

## Core User Interface Components

### 1. Authentication Pages
**Scope**: Basic, functional authentication without social login options

#### Login Page
- **Layout**: Centered card design with clean form
- **Fields**: Email, Password with "Remember Me" option
- **Actions**: Login button, "Forgot Password" link, "Sign Up" link
- **Validation**: Real-time validation with clear error messages
- **Mobile**: Single-column layout, large touch targets

#### Registration Page
- **Layout**: Similar to login with additional fields
- **Fields**: Name, Email, Password, Confirm Password
- **Actions**: Register button, "Already have an account" link
- **Validation**: Password strength indicator, email format validation

#### Password Reset
- **Layout**: Simple form with clear instructions
- **Flow**: Email input → Confirmation message → Reset form
- **Feedback**: Clear success/error states

### 2. Dashboard (Main Screen)
**Scope**: Overview of user's reading progress with basic statistics

#### Header Section
- **User Greeting**: "Good morning, [Name]" with contextual time-based messaging
- **Quick Stats Bar**: Current streak, total chapters read, active book count
- **Visual Design**: Clean header with subtle background, prominent streak counter

#### Today's Reading Section
- **Quick Log Button**: Prominent "Log Today's Reading" call-to-action
- **Recent Activity**: Last 2-3 reading entries with book/chapter information
- **Motivation Message**: Encouraging text based on current streak status

#### Progress Overview
- **Current Streak**: Large, prominent display with flame/streak icon
- **This Week**: Simple bar showing days read this week (7-day view)
- **Quick Stats**: Total chapters, reading days, books in progress
- **Visual Design**: Card-based layout with clear visual hierarchy

### 3. Reading Log Entry Form
**Scope**: Simple, fast form for logging daily Bible reading

#### Bible Reference Selection
- **Book Selector**: Dropdown with all 66 Bible books, organized by Testament
- **Chapter Selector**: Dynamic dropdown based on selected book
- **Multiple Chapters**: Option to select chapter ranges (e.g., "1-3")
- **Validation**: Ensure valid book/chapter combinations

#### Notes Section (Optional)
- **Text Area**: Simple plain text input for reflection notes
- **Character Limit**: 500 characters maximum (displayed with counter)
- **Placeholder**: Encouraging prompt text
- **Mobile**: Auto-resize textarea, easy typing experience

#### Form Actions
- **Save Button**: Primary action, prominent placement
- **Cancel**: Secondary action to return to dashboard
- **Validation**: Real-time feedback on required fields

### 4. Reading History View
**Scope**: Complete chronological list of all reading entries

#### List Display
- **Entry Format**: Date, Bible reference, notes preview (if any)
- **Sorting**: Reverse chronological (newest first)
- **Pagination**: Load more entries as user scrolls
- **Search**: Simple text search through Bible references and notes

#### Entry Details
- **Expandable Cards**: Tap to view full notes
- **Edit Option**: Quick edit for recent entries (last 7 days)
- **Visual Indicators**: Different styling for entries with/without notes

### 5. Book Completion Grid (MVP Feature)
**Scope**: Visual progress tracking for all 66 Bible books with percentage completion

#### Testament Toggle
- **Toggle Buttons**: Switch between Old Testament (39 books) and New Testament (27 books)
- **Visual Design**: Clean toggle with active state highlighting
- **Default View**: Old Testament selected by default

#### Progress Overview
- **Testament Label**: "Old Testament" or "New Testament" 
- **Overall Percentage**: Large, prominent display (e.g., "34%")
- **Progress Bar**: Visual bar showing testament completion percentage
- **Statistics Summary**: 
  - Completed books count (green background)
  - In Progress books count (blue background)  
  - Not Started books count (gray background)

#### Books Grid
- **Layout**: Responsive grid (2-6 columns based on screen size)
- **Book Cards**: Each book shows:
  - Book name (truncated if needed)
  - Completion percentage (e.g., "34%")
  - Visual status indicator (color-coded border/background)
  - Mini progress bar for in-progress books
  - Completion badge for finished books
- **Status Colors**:
  - **Completed**: Green (#66CC99) with completion badge
  - **In Progress**: Blue (#3366CC) with mini progress bar
  - **Not Started**: Light gray/white with subtle border
- **Hover/Tap**: Tooltip showing detailed progress (e.g., "Genesis: 12/50 chapters (24%)")

#### Legend
- **Visual Key**: Shows what each color/status means
- **Position**: Bottom of grid, horizontally centered
- **Icons**: Small colored squares matching the book card styling

### 6. Calendar Heat Map
**Scope**: GitHub-style calendar showing reading consistency

#### Calendar Display
- **Layout**: Grid showing last 365 days (or current year)
- **Cell Colors**: Intensity based on reading activity
  - No reading: Light gray
  - 1 chapter: Light green
  - 2-3 chapters: Medium green
  - 4+ chapters: Dark green
- **Mobile**: Horizontally scrollable, shows last 3-4 months in view

#### Interactive Elements
- **Hover/Tap**: Show date and reading details for each day
- **Legend**: Color intensity explanation
- **Navigation**: Scroll to see different time periods

## Navigation & Layout

### Primary Navigation
- **Dashboard**: Home/overview (default view)
- **Log Reading**: Quick access to entry form
- **History**: View all past entries
- **Progress**: Book completion grid and calendar view

### Mobile-First Considerations
- **Bottom Navigation**: Primary nav tabs at bottom for thumb accessibility
- **Touch Targets**: Minimum 44px touch targets for all interactive elements
- **Readable Text**: Minimum 16px font size, good line-height
- **Fast Loading**: Optimized images and minimal JavaScript

## Responsive Design Requirements

### Mobile (320px - 768px)
- **Single Column**: All content stacked vertically
- **Large Touch Targets**: Easy thumb navigation
- **Readable Text**: 16px+ font sizes
- **Simplified Navigation**: Bottom tab bar or hamburger menu

### Tablet (768px - 1024px)
- **Two Column**: Some sections can use side-by-side layout
- **Larger Grids**: Book completion grid shows more columns
- **Enhanced Spacing**: More whitespace for better visual hierarchy

### Desktop (1024px+)
- **Multi-Column**: Dashboard can show multiple sections side-by-side
- **Larger Calendar**: Full year view for heat map
- **Enhanced Interactions**: Hover states for better feedback

## Accessibility Requirements

### Visual Accessibility
- **Color Contrast**: WCAG AA compliance (4.5:1 ratio minimum)
- **Color Independence**: Information not conveyed by color alone
- **Focus Indicators**: Clear focus states for keyboard navigation
- **Text Scaling**: Interface remains functional at 200% zoom

### Interaction Accessibility
- **Keyboard Navigation**: All interactive elements accessible via keyboard
- **Screen Reader**: Proper semantic HTML and ARIA labels
- **Touch Accessibility**: 44px minimum touch targets
- **Clear Language**: Simple, direct copy throughout

## Performance Requirements

### Loading Performance
- **Initial Load**: Under 3 seconds on 3G connection
- **Subsequent Navigation**: Under 1 second for cached content
- **Image Optimization**: Compressed images with appropriate formats

### Interaction Performance
- **Form Submission**: Immediate feedback, under 500ms response
- **Search Results**: Real-time search with debounced input
- **Smooth Animations**: 60fps for all transitions and animations

## Data Display Specifications

### Statistics Display
**Scope**: Basic numerical statistics only (Advanced analytics are post-MVP)

#### Dashboard Statistics
- **Current Streak**: Days count with visual indicator
- **Longest Streak**: Historical best with comparison to current
- **Total Chapters**: Lifetime reading count
- **Books Started**: Count of books with at least one chapter read
- **This Week**: Simple count of days read in current week

#### Visual Representation
- **Numbers**: Large, easy-to-read typography
- **Icons**: Simple, recognizable icons for each stat
- **Comparison**: Basic "up/down" indicators where relevant
- **No Complex Charts**: Avoid graphs, charts, or trend analysis (those are post-MVP advanced analytics)

### Notes Display
**Scope**: Plain text notes with basic formatting

#### Notes Functionality
- **Input**: Simple textarea, plain text only
- **Character Limit**: 500 characters with live counter
- **Display**: Plain text with line breaks preserved
- **Search**: Text search through notes content
- **No Rich Text**: No formatting, links, or advanced features (post-MVP)

## Error States & Feedback

### Form Validation
- **Real-time**: Validate fields as user types
- **Clear Messages**: Specific, actionable error messages
- **Visual Indicators**: Red borders, error icons
- **Success States**: Green indicators for successful actions

### Network Issues
- **Offline Detection**: Show when user is offline
- **Retry Options**: Clear retry buttons for failed actions
- **Loading States**: Spinners and skeleton screens during loading

### Empty States
- **No Data**: Encouraging messages when user has no reading history
- **Onboarding**: Helpful prompts for first-time users
- **Motivational**: Positive messaging that encourages action

## Content Strategy

### Motivational Messaging
- **Positive Tone**: Encouraging, never guilt-inducing
- **Biblical References**: Subtle incorporation of relevant verses
- **Progress Celebration**: Acknowledge milestones and achievements
- **Gentle Reminders**: Helpful nudges without pressure

### Microcopy
- **Button Labels**: Clear action words ("Log Reading", "View History")
- **Helper Text**: Contextual help without overwhelming
- **Error Messages**: Friendly, solution-oriented language
- **Success Messages**: Celebratory but not overly effusive

## Future Considerations (Post-MVP)

The following features are explicitly **excluded from MVP** but may be considered for future releases:

### Post-MVP Features (Free Tier)
- **Achievement/Badge System**: Milestone celebrations and visual rewards
- **Social Authentication**: Google, Facebook, Apple sign-in options
- **Advanced Statistics**: Detailed charts, reading patterns, trend analysis
- **Enhanced Notes**: Rich text formatting, tagging, search enhancements
- **Milestone Recognition**: Special celebrations for streaks and completions

### Post-MVP Features (Potential Pro Tier)
- **Advanced Analytics**: Detailed reading insights, pattern analysis, comparative statistics
- **Enhanced Badge System**: Premium badge designs, exclusive achievements
- **Powerful Journaling**: Rich text editor, advanced organization features
- **Export Options**: Data export, backup features
- **Premium Support**: Priority customer support

---

*This document focuses exclusively on MVP features that provide core value to users while establishing a foundation for future enhancements. All described features are confirmed for the free tier and initial launch.*
