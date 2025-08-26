# Landing Page Refactoring Tasks

## Overview
Refactor the current landing page to align with the new empathy-first, research-based content strategy and showcase weekly goals/streaks functionality.

## Tasks

- [x] 1. Update hero section with empathy-first messaging and SEO optimization
  - Replace current headline "Build Your Bible Reading Habit" with empathy-first "Struggling to Stay Consistent with Bible Reading? You're Not Alone."
  - Update subheadline to research-based messaging about 4+ days per week creating dramatic spiritual growth
  - Change primary CTA to "Start Building Life-Changing Habits - Free" focusing on journey vs tracking
  - Add supportive note "( Based on proven research " No rigid daily pressure " Free forever"
  - Naturally integrate "Bible tracking app" and "Bible reading tracker" keywords throughout section
  - Update verse reference to maintain spiritual grounding while supporting new messaging
  - _Requirements: 1.1, 1.2, 5.4, 5.5_

- [x] 2. Expand features section from 4 to 6 features with weekly goals/streaks emphasis
  - Add "Research-Based Weekly Goals" as primary featured card with gradient/accent styling
    - Include 4-day weekly goal explanation based on studies of 100,000+ people
    - Mention 228% more spiritual growth vs 1-3 days per week
  - Add "Weekly Streaks" as primary featured card with gradient/accent styling  
    - Explain consecutive weeks of achieved goals building momentum
    - Focus on long-term consistency tracking
  - Update existing "Daily Reading Log" description to include "Bible tracking" terminology
  - Rename "Streak Tracking" to "Daily Streak Tracking" for clarity vs weekly streaks
  - Update "Visual Progress" to "Book Completion Grid" with specific mention of 66 Bible books
  - Enhance "Reading Statistics" to include new summary stats (days read, total chapters, Bible progress %, avg chapters/day)
  - Reorganize layout to 3x2 grid on desktop, 2x3 on tablet, single column on mobile
  - Apply featured styling to weekly goals and weekly streaks cards
  - _Requirements: 2.1, 2.4, 2.5, 2.6_

- [x] 3. Replace "How It Works" section with research-based problem-solution format
  - Update section headline to "The Science Behind Life-Changing Bible Reading"
  - Add research-solution grid with two cards side-by-side:
    - Research card: "The Research" with 📊 icon explaining studies of 100,000+ people showing 4+ days per week creates 228% more spiritual growth vs 1-3 days
    - Solution card: "Our Approach" with 🎯 icon explaining how Delight helps hit proven 4-day threshold with flexible weekly goals and gentle accountability
  - Keep existing 3-step process but update content to emphasize research-backed approach:
    - Step 1: "Read & Log" - emphasize 30-second logging with Bible tracking terminology
    - Step 2: "See Progress" - mention weekly goals, weekly streaks, daily streaks, and book completion grids  
    - Step 3: "Stay Motivated" - focus on research-based gentle accountability and progress celebration
  - Update section styling with light background for visual separation
  - Naturally integrate "Bible tracking app" and "habit tracker" keywords throughout section
  - _Requirements: 4.1, 4.2, 4.3, 5.4, 5.5_

- [x] 4. Update SEO metadata and structured data for Bible tracking focus
  - Update page title to "Delight - Bible Tracking App | Free Bible Reading Tracker with Weekly Goals"
  - Enhance meta description to include primary keywords and weekly goals feature
  - Expand keywords to include "bible tracking app", "bible reading tracker", "Power of 4 bible reading", "research-based bible habits"
  - Update Open Graph title and description to highlight Bible tracking and weekly goals
  - Enhance structured data featureList to include weekly goals, weekly streaks, and Bible tracking terminology
  - Update screenshot reference in structured data to new desktop image when available
  - _Requirements: 5.1, 5.2, 5.4, 5.5_

- [x] 5. Update screenshot references for new app interface (implementation ready)
  - Update desktop screenshot reference from `desktop_100.png` to `desktop_101.png`
  - Update mobile screenshots to use `mobile_101.png` (weekly features) and `mobile_102.png` (daily stats)
  - Update alt text for desktop screenshot to describe weekly goals, weekly streaks, daily streaks, and book completion grid
  - Update alt text for first mobile screenshot to describe weekly goal and weekly streak widgets
  - Update alt text for second mobile screenshot to describe daily streak and enhanced summary stats
  - Ensure all alt text includes SEO keywords naturally
  - _Requirements: 8.1, 8.2, 8.3, 8.4_

- [x] 6. Implement natural keyword integration throughout all content
  - Review hero section copy to naturally include "Bible reading tracker" and "Bible tracking app"
  - Update feature descriptions to include secondary keywords without stuffing
  - Enhance "How It Works" section to mention Bible tracking and habit building terminology
  - Update final CTA section to include tracking-related keywords in headline or description
  - Review footer content for appropriate keyword placement
  - Ensure all keyword integration feels natural and user-focused, not forced
  - _Requirements: 5.4, 5.5_

- [x] 7. Update final CTA section with research-based messaging
  - Update headline from "Ready to Build Your Reading Habit?" to align with research messaging
  - Consider "Ready to Experience the Power of 4-Day Bible Reading?" or similar
  - Update description to mention research-based approach and gentle accountability
  - Ensure CTA button text aligns with hero section messaging
  - Maintain gradient background and visual styling
  - _Requirements: 3.1, 3.2_

- [ ] 8. Test and validate all changes
  - Verify responsive design across mobile, tablet, and desktop
  - Test all CTA links redirect properly to registration/login pages
  - Validate SEO improvements with meta tag checkers
  - Confirm accessibility standards maintained with new content
  - Test page load performance with updated content and images
  - Validate keyword density is natural and not over-optimized
  - _Requirements: 9.1, 9.2, 9.3, 9.4_

## Notes
- Screenshots `desktop_101.png`, `mobile_101.png`, and `mobile_102.png` are available in `public/images/screenshots/`
- Focus on empathy-first, grace-based messaging that addresses struggles before solutions
- Emphasize proven research and achievable goals over rigid daily requirements
- Maintain existing technical infrastructure and component structure
- All changes should feel natural and user-focused, avoiding keyword stuffing