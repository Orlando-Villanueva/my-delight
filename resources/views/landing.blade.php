<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ config('app.name', 'Delight') }} - Build Your Bible Reading Habit | Free Bible Tracker</title>
    <meta name="description" content="Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture. Free Bible reading habit tracker with beautiful progress visualization.">
    <meta name="keywords" content="bible reading, habit tracker, scripture reading, bible study, reading streaks, christian app, daily bible reading, bible progress tracker, scripture habit">
    <meta name="author" content="Delight">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ config('app.url') }}">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="geo.region" content="Global">
    <meta name="geo.placename" content="Worldwide">
    
    <!-- Sitemap Reference -->
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ config('app.url') }}/sitemap.xml">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Delight - Build Your Bible Reading Habit">
    <meta property="og:description" content="Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture. Free Bible reading habit tracker with beautiful progress visualization.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:image" content="{{ asset('images/logo-192.png') }}">
    <meta property="og:site_name" content="Delight">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Delight - Build Your Bible Reading Habit">
    <meta name="twitter:description" content="Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture.">
    <meta name="twitter:image" content="{{ asset('images/logo-192.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "Delight",
            "description": "Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture. Free Bible reading habit tracker with beautiful progress visualization.",
            "url": "{{ config('app.url') }}",
            "applicationCategory": "LifestyleApplication",
            "operatingSystem": "Web Browser",
            "browserRequirements": "Requires JavaScript. Requires HTML5.",
            "softwareVersion": "1.0",
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "5.0",
                "ratingCount": "1"
            },
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "USD",
                "availability": "https://schema.org/InStock"
            },
            "author": {
                "@type": "Organization",
                "name": "Delight"
            },
            "keywords": "bible reading, habit tracker, scripture reading, bible study, reading streaks, christian app",
            "screenshot": "{{ asset('images/screenshots/desktop_100.png') }}",
            "featureList": [
                "Daily Reading Log",
                "Streak Tracking", 
                "Visual Progress",
                "Reading Statistics"
            ]
        }
    </script>
</head>

<body class="font-sans antialiased bg-white">
    <!-- Skip to main content link for screen readers -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Skip to main content
    </a>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100" role="navigation" aria-label="Main navigation">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2 text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md" aria-label="Delight - Go to homepage">
                        <img
                            src="{{ asset('images/logo-64.png') }}"
                            srcset="{{ asset('images/logo-64.png') }} 1x, {{ asset('images/logo-64-2x.png') }} 2x"
                            alt="Delight logo - Bible reading habit tracker"
                            class="w-8 h-8"
                            width="32"
                            height="32"
                            loading="eager" />
                        <span>Delight</span>
                    </a>
                </div>

                <!-- Navigation Actions -->
                <div class="flex items-center space-x-4" role="group" aria-label="Account actions">
                    @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md px-2 py-1" aria-label="Go to your dashboard">
                        Dashboard
                    </a>
                    @else
                    <x-ui.button variant="ghost" href="{{ route('login') }}" aria-label="Sign in to your account">
                        Sign In
                    </x-ui.button>
                    <x-ui.button variant="accent" href="{{ route('register') }}" aria-label="Create a new account">
                        Get Started
                    </x-ui.button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16" id="main-content" role="main">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-bl from-blue-50 to-white py-20 lg:py-32" aria-labelledby="hero-heading">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-2 items-center">
                    <!-- Hero Content -->
                    <div class="text-center lg:text-left">
                        <h1 id="hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                            Build Your Bible Reading Habit
                        </h1>
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                            Track your daily reading, maintain streaks, and visualize your progress through Scripture with our simple, motivating habit tracker.
                        </p>
                        <blockquote class="text-sm text-gray-700 italic mb-8" cite="https://www.biblegateway.com/passage/?search=Psalm%201%3A2&version=ESV">
                            <p>"His delight is in the law of the LORD, and on his law he meditates day and night." - Psalm 1:2</p>
                        </blockquote>

                        <!-- Primary CTA -->
                        <div class="space-y-4">
                            <x-ui.button variant="accent" size="lg" href="{{ route('register') }}" aria-describedby="cta-description">
                                Start Reading Today
                            </x-ui.button>
                            <p id="cta-description" class="text-sm text-gray-700">
                                Free to use • No signup fees
                            </p>
                        </div>
                    </div>

                    <!-- Hero Visual -->
                    <div class="relative" role="img" aria-label="Screenshots of Delight Bible reading tracker application">
                        <!-- Desktop Screenshot - Hidden on mobile -->
                        <div class="hidden lg:block bg-white rounded-2xl shadow-2xl p-0 transform rotate-1">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/desktop_100.png') }}"
                                    alt="Delight dashboard showing Bible reading progress with streak counter, book completion grid, and daily reading log interface"
                                    class="w-full h-auto max-w-full"
                                    width="800"
                                    height="600"
                                    loading="lazy" />
                            </div>
                        </div>

                        <!-- Mobile Screenshot - Shown on mobile, floating on desktop -->
                        <div class="lg:hidden bg-white rounded-2xl shadow-2xl p-0 w-64 mx-auto mt-8">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/mobile_100.png') }}"
                                    alt="Delight mobile interface showing book and chapter selection for logging daily Bible reading"
                                    class="w-full h-auto"
                                    width="256"
                                    height="512"
                                    loading="lazy" />
                            </div>
                        </div>

                        <!-- Mobile Screenshot - Floating (Desktop only) -->
                        <div class="hidden lg:block absolute -bottom-6 -right-6 w-36 sm:w-40 lg:w-48 bg-white rounded-xl shadow-xl p-0 transform rotate-6">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/mobile_100.png') }}"
                                    alt="Delight mobile interface showing book and chapter selection for logging daily Bible reading"
                                    class="w-full h-auto max-w-full"
                                    width="192"
                                    height="384"
                                    loading="lazy" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-primary-50" aria-labelledby="features-heading">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 id="features-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Everything You Need to Stay Consistent
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Simple tools designed to help you build and maintain a meaningful Bible reading habit.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8" role="list" aria-label="Key features of Delight">
                    <!-- Feature 1: Daily Reading Log -->
                    <div role="listitem">
                        <x-ui.card elevated class="hover:shadow-xl transition-shadow h-full">
                            <x-ui.card-content>
                                <div class="text-4xl mb-4" role="img" aria-label="Book icon">📖</div>
                                <x-ui.card-title>Daily Reading Log</x-ui.card-title>
                                <p class="text-gray-600 leading-relaxed mt-3">
                                    Easily track which chapters you've read with our intuitive book and chapter selector. Simple logging keeps you focused on reading, not recording.
                                </p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>

                    <!-- Feature 2: Streak Tracking -->
                    <div role="listitem">
                        <x-ui.card class="bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-lg hover:shadow-xl transition-shadow h-full">
                            <x-ui.card-content>
                                <div class="text-4xl mb-4" role="img" aria-label="Fire icon representing streaks">🔥</div>
                                <x-ui.card-title class="text-white">Streak Tracking</x-ui.card-title>
                                <p class="text-blue-100 leading-relaxed mt-3">
                                    Build momentum with reading streaks and get motivated by your consistency. See your current streak and longest streak to stay encouraged.
                                </p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>

                    <!-- Feature 3: Visual Progress -->
                    <div role="listitem">
                        <x-ui.card elevated class="hover:shadow-xl transition-shadow h-full">
                            <x-ui.card-content>
                                <div class="text-4xl mb-4" role="img" aria-label="Chart icon representing progress">📊</div>
                                <x-ui.card-title>Visual Progress</x-ui.card-title>
                                <p class="text-gray-600 leading-relaxed mt-3">
                                    See your journey through Scripture with our beautiful book completion grid. Watch as you fill in each book of the Bible.
                                </p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>

                    <!-- Feature 4: Reading Statistics -->
                    <div role="listitem">
                        <x-ui.card elevated class="hover:shadow-xl transition-shadow h-full">
                            <x-ui.card-content>
                                <div class="text-4xl mb-4" role="img" aria-label="Graph icon representing statistics">📈</div>
                                <x-ui.card-title>Reading Statistics</x-ui.card-title>
                                <p class="text-gray-600 leading-relaxed mt-3">
                                    Track your total chapters read, books completed, and longest streaks. Celebrate your progress with meaningful statistics.
                                </p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 bg-white" aria-labelledby="how-it-works-heading">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 id="how-it-works-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Simple Steps to Build Your Habit
                    </h2>
                    <p class="text-xl text-gray-600">
                        Get started with your Bible reading journey in just a few easy steps.
                    </p>
                </div>

                <!-- Steps Grid -->
                <ol class="grid md:grid-cols-3 gap-8" role="list" aria-label="Steps to get started with Delight">
                    <!-- Step 1 -->
                    <li class="text-center">
                        <div class="bg-accent-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                            <span class="text-2xl font-bold text-accent-600">1</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Create Your Account</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Sign up for free in seconds. No complicated setup process required.
                        </p>
                    </li>

                    <!-- Step 2 -->
                    <li class="text-center">
                        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                            <span class="text-2xl font-bold text-blue-600">2</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Log Your Reading</h3>
                        <p class="text-gray-600 leading-relaxed">
                            After reading, simply select the book and chapters you've completed. Takes less than 30 seconds.
                        </p>
                    </li>

                    <!-- Step 3 -->
                    <li class="text-center">
                        <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6" aria-hidden="true">
                            <span class="text-2xl font-bold text-green-600">3</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Watch Your Progress</h3>
                        <p class="text-gray-600 leading-relaxed">
                            See your streaks grow, books fill up, and statistics improve. Stay motivated by your progress.
                        </p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="py-20 bg-gradient-to-br from-[#3366CC] to-[#2952A3]" aria-labelledby="final-cta-heading">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 id="final-cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Ready to Build Your Reading Habit?
                </h2>
                <p class="text-xl text-white mb-8">
                    Join other readers discovering delight in daily Bible reading.
                </p>
                <x-ui.button variant="accent" size="lg" href="{{ route('register') }}" aria-label="Sign up to start your Bible reading journey">
                    Start Your Journey Today
                </x-ui.button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12" role="contentinfo" aria-label="Site footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4">Delight</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Building consistent Bible reading habits through simple tracking and motivating progress visualization.
                    </p>
                </div>

                <!-- Quick Links -->
                <nav class="md:col-span-1" aria-labelledby="quick-links-heading">
                    <h4 id="quick-links-heading" class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900 rounded-sm">Get Started</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900 rounded-sm">Sign In</a></li>
                    </ul>
                </nav>

                <!-- Legal -->
                <nav class="md:col-span-1" aria-labelledby="legal-links-heading">
                    <h4 id="legal-links-heading" class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('privacy-policy') }}" class="text-gray-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900 rounded-sm">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-of-service') }}" class="text-gray-300 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900 rounded-sm">Terms of Service</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    © {{ date('Y') }} Delight. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>