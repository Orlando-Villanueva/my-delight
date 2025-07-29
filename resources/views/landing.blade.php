<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ config('app.name', 'Delight') }} - Build Your Bible Reading Habit | Free Bible Tracker</title>
    <meta name="description" content="Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture. Free Bible reading habit tracker with beautiful progress visualization.">
    <meta name="keywords" content="bible reading, habit tracker, scripture reading, bible study, reading streaks, christian app">
    <meta name="author" content="Delight">
    <link rel="canonical" href="{{ config('app.url') }}">

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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "Delight",
            "description": "Track your daily Bible reading, maintain streaks, and visualize your progress through Scripture.",
            "url": "{{ config('app.url') }}",
            "applicationCategory": "LifestyleApplication",
            "operatingSystem": "Web Browser",
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "USD"
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2 text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                        <img
                            src="{{ asset('images/logo-64.png') }}"
                            srcset="{{ asset('images/logo-64.png') }} 1x, {{ asset('images/logo-64-2x.png') }} 2x"
                            alt="Delight Logo"
                            class="w-8 h-8" />
                        <span>Delight</span>
                    </a>
                </div>

                <!-- Navigation Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                        Dashboard
                    </a>
                    @else
                    <x-ui.button variant="ghost" href="{{ route('login') }}">
                        Sign In
                    </x-ui.button>
                    <x-ui.button variant="accent" href="{{ route('register') }}">
                        Get Started
                    </x-ui.button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-bl from-blue-50 to-white py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-2 items-center">
                    <!-- Hero Content -->
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                            Build Your Bible Reading Habit
                        </h1>
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                            Track your daily reading, maintain streaks, and visualize your progress through Scripture with our simple, motivating habit tracker.
                        </p>
                        <p class="text-sm text-gray-500 italic mb-8">
                            "His delight is in the law of the LORD, and on his law he meditates day and night." - Psalm 1:2
                        </p>

                        <!-- Primary CTA -->
                        <div class="space-y-4">
                            <x-ui.button variant="accent" size="lg" href="{{ route('register') }}">
                                Start Reading Today
                            </x-ui.button>
                            <p class="text-sm text-gray-500">
                                Free to use • No signup fees
                            </p>
                        </div>
                    </div>

                    <!-- Hero Visual -->
                    <div class="relative">
                        <!-- Desktop Screenshot - Hidden on mobile -->
                        <div class="hidden lg:block bg-white rounded-2xl shadow-2xl p-0 transform rotate-1 hover:rotate-0 transition-transform duration-300">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/desktop.png') }}"
                                    alt="Delight Dashboard - Bible Reading Progress Tracker"
                                    class="w-full h-auto max-w-full"
                                    loading="lazy" />
                            </div>
                        </div>

                        <!-- Mobile Screenshot - Shown on mobile, floating on desktop -->
                        <div class="lg:hidden bg-white rounded-2xl shadow-2xl p-0 w-64 mx-auto mt-8">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/mobile.png') }}"
                                    alt="Delight Mobile App - Reading Log Interface"
                                    class="w-full h-auto"
                                    loading="lazy" />
                            </div>
                        </div>

                        <!-- Mobile Screenshot - Floating (Desktop only) -->
                        <div class="hidden lg:block absolute -bottom-6 -right-6 w-36 sm:w-40 lg:w-48 bg-white rounded-xl shadow-xl p-0 transform rotate-6 hover:rotate-3 transition-transform duration-300">
                            <div class="rounded-lg overflow-hidden">
                                <img
                                    src="{{ asset('images/screenshots/mobile.png') }}"
                                    alt="Delight Mobile App - Reading Log Interface"
                                    class="w-full h-auto max-w-full"
                                    loading="lazy" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-primary-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Everything You Need to Stay Consistent
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Simple tools designed to help you build and maintain a meaningful Bible reading habit.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8">
                    <!-- Feature 1: Daily Reading Log -->
                    <x-ui.card elevated class="hover:shadow-xl transition-shadow">
                        <x-ui.card-content>
                            <div class="text-4xl mb-4">📖</div>
                            <x-ui.card-title>Daily Reading Log</x-ui.card-title>
                            <p class="text-gray-600 leading-relaxed mt-3">
                                Easily track which chapters you've read with our intuitive book and chapter selector. Simple logging keeps you focused on reading, not recording.
                            </p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <!-- Feature 2: Streak Tracking -->
                    <x-ui.card class="bg-gradient-to-br from-[#3366CC] to-[#2952A3] text-white shadow-lg hover:shadow-xl transition-shadow">
                        <x-ui.card-content>
                            <div class="text-4xl mb-4">🔥</div>
                            <x-ui.card-title class="text-white">Streak Tracking</x-ui.card-title>
                            <p class="text-blue-100 leading-relaxed mt-3">
                                Build momentum with reading streaks and get motivated by your consistency. See your current streak and longest streak to stay encouraged.
                            </p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <!-- Feature 3: Visual Progress -->
                    <x-ui.card elevated class="hover:shadow-xl transition-shadow">
                        <x-ui.card-content>
                            <div class="text-4xl mb-4">📊</div>
                            <x-ui.card-title>Visual Progress</x-ui.card-title>
                            <p class="text-gray-600 leading-relaxed mt-3">
                                See your journey through Scripture with our beautiful book completion grid. Watch as you fill in each book of the Bible.
                            </p>
                        </x-ui.card-content>
                    </x-ui.card>

                    <!-- Feature 4: Reading Statistics -->
                    <x-ui.card elevated class="hover:shadow-xl transition-shadow">
                        <x-ui.card-content>
                            <div class="text-4xl mb-4">📈</div>
                            <x-ui.card-title>Reading Statistics</x-ui.card-title>
                            <p class="text-gray-600 leading-relaxed mt-3">
                                Track your total chapters read, books completed, and longest streaks. Celebrate your progress with meaningful statistics.
                            </p>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Simple Steps to Build Your Habit
                    </h2>
                    <p class="text-xl text-gray-600">
                        Get started with your Bible reading journey in just a few easy steps.
                    </p>
                </div>

                <!-- Steps Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="bg-accent-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-accent-600">1</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Create Your Account</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Sign up for free in seconds. No complicated setup process required.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-blue-600">2</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Log Your Reading</h3>
                        <p class="text-gray-600 leading-relaxed">
                            After reading, simply select the book and chapters you've completed. Takes less than 30 seconds.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                            <span class="text-2xl font-bold text-green-600">3</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Watch Your Progress</h3>
                        <p class="text-gray-600 leading-relaxed">
                            See your streaks grow, books fill up, and statistics improve. Stay motivated by your progress.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="py-20 bg-gradient-to-br from-[#3366CC] to-[#2952A3]">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Ready to Build Your Reading Habit?
                </h2>
                <p class="text-xl text-white mb-8">
                    Join thousands of readers who are making consistent progress through Scripture.
                </p>
                <x-ui.button variant="accent" size="lg" href="{{ route('register') }}">
                    Start Your Journey Today
                </x-ui.button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4">Delight</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Building consistent Bible reading habits through simple tracking and motivating progress visualization.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Get Started</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Sign In</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} Delight. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>