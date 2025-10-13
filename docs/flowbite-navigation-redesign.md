# Flowbite Navigation Redesign

**Status:** Planned
**Date:** 2025-10-11
**Related Issue:** DEL-167 Desktop Nav Bar Active State Inconsistency

## Overview

This document outlines the complete redesign of the application's navigation system, including:
1. Removal of the Alpine.js active state tracking system
2. Implementation of Flowbite navigation components
3. Browser back/forward button support with HTMX

## Goals

- Remove all active state highlighting from navigation (desktop sidebar and mobile bottom bar)
- Replace current navigation with Flowbite components styled with brand colors
- Maintain HTMX-driven navigation with URL changes
- Add browser back/forward button support
- Preserve hover states and dark mode support

## Current System (To Be Removed)

### Active State Management
- Alpine.js `currentView` variable tracks active page
- Dynamic `:class` bindings highlight active navigation items
- `toggleAddButton()` method with disabled state on create page
- Manual `updateTitle()` function

### Navigation Structure
**Desktop:**
- Left sidebar with navigation items
- Header with page title and Log Reading button
- User profile section in sidebar

**Mobile:**
- Top header with logo and user menu
- Bottom navigation bar with elevated circular FAB button
- Three navigation items: Dashboard, Log Reading (FAB), History

## New System (To Be Implemented)

### Navigation Structure

**Desktop:**
- **Top Navbar:** Fixed, full-width, contains:
  - Logo (left)
  - Profile dropdown with sign out button (right)
- **Left Sidebar:** Contains navigation items:
  - Dashboard
  - Log Reading
  - History
- Hover states only (no active highlighting)

**Mobile:**
- **Top Navbar:** Scrolls with content, contains:
  - Logo (left)
  - Profile dropdown with sign out button (right)
  - NO hamburger menu
- **Bottom Application Bar:** Fixed to bottom, contains:
  - Dashboard (left)
  - Log Reading (center circular button with accent color)
  - History (right)

### Browser Navigation Support

**Problem:** Currently, browser back/forward buttons don't update page content or title.

**Solution:**
1. Listen for `popstate` event (fires when user clicks back/forward)
2. When event fires, tell HTMX to fetch content for the new URL
3. HTMX automatically updates title from the fetched HTML
4. Server includes title tag at top of partial HTML fragments

**Implementation:**
```javascript
window.addEventListener('popstate', (event) => {
    htmx.ajax('GET', window.location.href, {
        target: '#page-container',
        swap: 'innerHTML'
    });
});
```

## Implementation Plan

### Phase 1: Install Flowbite

1. **Install NPM package:**
   ```bash
   npm install flowbite --save
   ```

2. **Update `resources/css/app.css`:**
   ```css
   @import "tailwindcss";
   @import "flowbite/src/themes/default";
   @plugin "flowbite/plugin";
   @source "../../node_modules/flowbite";
   ```

3. **Add Flowbite JS to layout:**
   ```html
   <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
   ```

4. **Build assets:**
   ```bash
   npm run build
   ```

### Phase 2: Browser Back/Forward Support

Add `popstate` event listener in `resources/views/layouts/authenticated.blade.php`:

```javascript
<script>
    // Browser back/forward button support
    window.addEventListener('popstate', (event) => {
        htmx.ajax('GET', window.location.href, {
            target: '#page-container',
            swap: 'innerHTML'
        });
    });
</script>
```

Ensure partial views (dashboard-content.blade.php, reading-log-form.blade.php, etc.) include title tags.

### Phase 3: Create Blade Components

#### 1. Desktop Sidebar Component
**File:** `resources/views/components/navigation/desktop-sidebar.blade.php`

**Features:**
- Flowbite sidebar structure
- HTMX navigation (hx-get, hx-target="#page-container", hx-push-url="true")
- Three navigation items: Dashboard, Log Reading, History
- Hover states only (no active state tracking)
- Brand color styling (primary-500 blue)
- Dark mode support

#### 2. Desktop Navbar Component
**File:** `resources/views/components/navigation/desktop-navbar.blade.php`

**Features:**
- Fixed top positioning (w-full)
- Logo (left side)
- Profile dropdown (right side) with:
  - User name and email
  - Sign out button (POST form to logout route)
- Dark mode support

#### 3. Mobile Navbar Component
**File:** `resources/views/components/navigation/mobile-navbar.blade.php`

**Features:**
- NOT sticky/fixed (scrolls with content)
- Logo (left side)
- Profile dropdown (right side)
- NO hamburger menu
- Same profile dropdown structure as desktop

#### 4. Mobile Bottom Bar Component
**File:** `resources/views/components/navigation/mobile-bottom-bar.blade.php`

**Features:**
- Fixed to bottom (bottom-4 positioning)
- Flowbite application bar structure
- Three items in grid:
  - Dashboard (left) - regular item with icon + label
  - Log Reading (center) - circular button with accent background
  - History (right) - regular item with icon + label
- HTMX navigation on all items
- Dark mode support

### Phase 4: Update Layout File

**File:** `resources/views/layouts/authenticated.blade.php`

**Remove:**
- All Alpine.js x-data with `currentView`, `previousView`, `toggleAddButton()`
- All `:class` bindings for active states
- All `@click` handlers that update `currentView`
- `updateTitle()` function and watcher
- Current sidebar markup (lines 88-177)
- Current desktop header (lines 181-213)
- Current mobile header (lines 217-260)
- Current mobile bottom navigation (lines 282-324)

**Add:**
```blade
<body>
    <div class="flex h-screen">
        <!-- Desktop: Navbar + Sidebar -->
        <x-navigation.desktop-navbar class="hidden lg:block" />
        <x-navigation.desktop-sidebar class="hidden lg:block" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:overflow-hidden">
            <!-- Mobile: Navbar (scrolls with content) -->
            <x-navigation.mobile-navbar class="lg:hidden" />

            <main class="flex-1 lg:overflow-y-auto">
                <div id="page-container" class="lg:flex lg:h-full container mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile: Bottom Bar -->
        <x-navigation.mobile-bottom-bar class="lg:hidden" />
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- Browser Navigation Support -->
    <script>
        window.addEventListener('popstate', (event) => {
            htmx.ajax('GET', window.location.href, {
                target: '#page-container',
                swap: 'innerHTML'
            });
        });
    </script>
</body>
```

### Phase 5: Brand Color Styling

**Primary Colors:**
- Primary blue: `#3366CC` (primary-500)
- Accent color for center FAB button

**Customization:**
- Replace Flowbite's default blue-600 with primary-500
- Replace Flowbite's default blue-700 (hover) with primary-600
- Center FAB button: accent-500 background
- Maintain Flowbite's structure and spacing
- Preserve dark mode utilities (dark:)

### Phase 6: Testing Checklist

- [ ] HTMX navigation works on desktop sidebar
- [ ] HTMX navigation works on mobile bottom bar
- [ ] URLs update correctly in browser address bar
- [ ] Browser back button fetches previous page content
- [ ] Browser forward button fetches next page content
- [ ] Page titles update on back/forward navigation
- [ ] Hover states work on all navigation items
- [ ] Profile dropdown opens/closes correctly
- [ ] Sign out button works
- [ ] Mobile bottom bar fixed to bottom
- [ ] Mobile navbar scrolls with content
- [ ] Desktop layout responsive at different screen sizes
- [ ] Dark mode works across all components
- [ ] Center FAB button styled with accent color
- [ ] No console errors

## References

- [Flowbite Laravel Installation](https://flowbite.com/docs/getting-started/laravel/)
- [Flowbite Sidebar with Navbar](https://flowbite.com/docs/components/sidebar/#sidebar-with-navbar)
- [Flowbite Bottom Navigation - Application Bar](https://flowbite.com/docs/components/bottom-navigation/#application-bar-example)
- [HTMX History Support](https://htmx.org/docs/#history)

## Success Criteria

1. No Alpine.js active state tracking remains
2. All navigation uses Flowbite components
3. Browser back/forward buttons work correctly
4. HTMX navigation maintained throughout
5. Brand colors applied to all components
6. Dark mode support preserved
7. Responsive design works on mobile and desktop
8. All tests pass
