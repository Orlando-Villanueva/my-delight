# Delight - Monetization Strategy

## 1. Guiding Philosophy: Mission-First, Supporter-Funded

Our monetization model is built on a philosophy of generosity, not restriction. The primary goal is to help the body of Christ develop a consistent Bible reading habit. Therefore, the core application will always be free and powerful enough to be a genuine blessing to anyone who uses it.

We will not employ intrusive ads, sell user data, or use manipulative tactics.

Revenue will be generated through an optional "Pro" tier, framed as a **"Supporter Plan."** Users who subscribe are not just buying features; they are supporting the mission, funding the operational costs (servers, databases), and enabling future development to bless even more people. The value exchange is twofold: users receive powerful enhancement tools, and the ministry receives the support it needs to thrive.

## 2. The Two-Tier Model: "Habit Builder" vs. "Deep Diver"

### The Radically Generous Free Tier ("Habit Builder")

This tier provides everything a user needs to successfully build and maintain a consistent Bible reading habit. It is designed to be best-in-class on its own.

**Free Features:**

- **Full Reading Log:** Unlimited logging of chapters or time spent reading.
- **Complete History View:** Users can see their entire reading history, not just a recent snapshot.
- **Core Statistics:**
  - Current Reading Streak (with 1-day grace period).
  - All-Time Longest Streak.
  - Total chapters read.
  - Books started vs. completed counts.
- **Visual Motivators:**
  - **Heat Map Calendar:** The full, interactive calendar view of reading history. This is a primary motivator and should be free.
  - **Book Completion Grid:** All 66 Bible books with percentage completion (e.g., "Genesis 34%", "John 100%"). Color-coded progress visualization.
- **Basic Note-Taking:** Ability to attach a simple, plain-text note (500 characters) to each reading log.
- **Achievement System:** (Post-MVP) Core achievement badges for book completions, streak milestones, and reading volume. Basic badge collection and simple sharing.

### The "Supporter" Pro Tier ("Deep Diver")

This tier is for engaged users who want to go deeper into their personal analytics and for those who wish to financially support the platform's mission.

**Pro Features:**

- **Advanced Statistics & Analytics:** The #1 value proposition. Comprehensive data insights including:
  - Detailed progress tracking with reading pace analysis and trends
  - Charts showing reading patterns by week, month, and year
  - Reading consistency insights and pattern recognition
  - Personal best records and goal predictions
  - Historical comparison and performance analytics
- **Enhanced Achievement System:** 
  - Premium badge designs with animations
  - Exclusive achievement categories and challenges
  - Advanced sharing options and achievement analytics
  - Custom badge goals and biblical milestone tracking
- **Powerful Journaling:**
  - A rich-text editor for notes with formatting (bold, italics, lists).
  - A dedicated, searchable journal view to filter and find all past notes.
- **Advanced Goal Setting:** Create and track custom, long-term goals beyond a simple daily habit (e.g., "Read the entire New Testament by Christmas").
- **Data Export:** Export reading log and journal history to CSV or PDF.
- **Appearance Customization:** Access to premium visual themes and custom app styling.

## 3. Technical Implementation Plan (Laravel + HTMX)

This plan integrates directly with your existing technical architecture.

### Step 1: Database Schema

In your users table migration, add fields to support subscriptions. Laravel Cashier (recommended below) will handle most of this, but it's good to be aware of the columns it will add, such as `stripe_id`, `pm_type`, `pm_last_four`, and `trial_ends_at`.

### Step 2: Subscription Management (Laravel Cashier)

The most robust and Laravel-native way to handle this is with **Laravel Cashier for Stripe**.

1. **Installation:**
   ```bash
   composer require laravel/cashier
   php artisan cashier:install
   php artisan migrate
   ```

2. **Configuration:**
   - Add the Billable trait to your `app/Models/User.php` model.

   ```php
   use Laravel\Cashier\Billable;

   class User extends Authenticatable
   {
       use Billable;
       // ...
   }
   ```

   - Configure your Stripe keys in the `.env` file.
   - In your Stripe dashboard, create subscription products: monthly, yearly, and lifetime options.

### Step 3: Feature Gating (Backend Logic)

Use Laravel's Gate or Policy features to control access to Pro features. This is clean and reusable.

1. **Define a Helper on the User Model:**
   In `app/Models/User.php`, create a simple method to check for an active subscription.
   
   ```php
   public function isPro(): bool
   {
       // Check for active subscription or lifetime purchase
       return $this->subscribed('default') || $this->hasLifetimeAccess();
   }
   
   public function hasLifetimeAccess(): bool
   {
       // Check for lifetime purchase flag or specific product
       return $this->lifetime_access === true;
   }
   ```

2. **Define a Gate:**
   In `app/Providers/AuthServiceProvider.php`, define a gate that uses this helper method.
   
   ```php
   use App\Models\User;
   use Illuminate\Support\Facades\Gate;

   public function boot(): void
   {
       // ...
       Gate::define('view-pro-features', function (User $user) {
           return $user->isPro();
       });
   }
   ```

3. **Protect Routes and Controllers:**
   - **In Controllers:** Protect methods that return pro-level data.

   ```php
   // In UserStatisticsService.php or a controller
   public function getAdvancedStatistics(User $user): array
   {
       if (Gate::denies('view-pro-features')) {
           // Or return an empty array, or throw an exception
           abort(403, 'This feature is for Pro supporters only.');
       }
       // ... logic for advanced analytics and detailed statistics
   }
   ```

   - **In Routes (web.php/api.php):** Protect entire endpoints.

   ```php
   Route::get('/stats/advanced', [StatisticsController::class, 'advanced'])
       ->middleware(['auth', 'can:view-pro-features']);
   ```

### Step 4: Frontend Implementation (Blade + HTMX + Alpine.js)

Conditionally render UI elements based on the user's "Pro" status using the `@can` Blade directive. This is perfect for a server-rendered HTMX architecture.

**Example: dashboard.blade.php**

```blade
<!-- This component is available to everyone -->
<div class="card">
    <h2>My Streak</h2>
    <div hx-get="/streak" hx-trigger="load">
        <!-- Streak count will be loaded here -->
    </div>
</div>

<!-- Book Completion Grid - Free for everyone -->
<div class="card">
    <h2>Bible Reading Progress</h2>
    <div hx-get="/stats/books" hx-trigger="load">
        <!-- Book completion grid with percentages -->
    </div>
</div>

<!-- This component is for Pro users ONLY -->
@can('view-pro-features')
    <div class="card card-pro">
        <h2>Advanced Analytics</h2>
        <div hx-get="/stats/advanced" hx-trigger="load">
            <!-- Detailed charts, trends, and analytics -->
        </div>
    </div>
@else
    <!-- Show a friendly upsell banner for free users -->
    <div class="card card-upsell">
        <h2>Go Deeper in Your Journey</h2>
        <p>
            Get detailed insights into your reading patterns, track advanced goals, and help support our mission to equip the saints.
        </p>
        <a href="{{ route('subscription.checkout') }}" class="button-primary">
            Become a Supporter
        </a>
    </div>
@endcan
```

This approach is efficient and secure. The server decides what the user can see, and HTMX simply places the rendered HTML. There's no complex state to manage on the client.

## 4. Pricing and Framing

- **Pricing:**
  - **Monthly:** $2.99 USD (reduced to encourage adoption)
  - **Yearly:** $29.99 USD (save 17% - "2 months free")
  - **Lifetime:** $99.99 USD (one-time purchase for dedicated supporters)
- **Framing:**
  - **Button Text:** Use "Become a Supporter" or "Support the Mission" instead of "Upgrade."
  - **Checkout Page:** Explicitly state that their contribution covers server costs, database maintenance, and allows the platform to remain free for those who cannot pay.

## 5. Future Roadmap Alignment

This model is perfectly aligned with your future plans.

- **Mobile Apps (iOS/Android):** When you build native apps, they will authenticate against the same Laravel backend. You can use a package like RevenueCat to handle native in-app purchases (IAP) and have it send a webhook to your Laravel server to update the user's subscription status. The `isPro()` method on your User model will continue to work seamlessly, whether the subscription originated from Stripe (web) or Apple/Google (mobile).
- **New Features:** As you build new features like Advanced Analytics or Enhanced Achievements, you can simply decide whether they are free or pro and protect them using the same `Gate::define('view-pro-features')`.
- **Badge System:** The achievement system will be implemented as a major feature release post-MVP, with core badges free for all users and enhanced features for Pro supporters.

This strategy provides a clear path to sustainability while holding true to your mission of service. It builds trust with your free users and offers genuine, compelling value to those who choose to become supporters.