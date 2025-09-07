<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Delight') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo-192.png') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#3366CC">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Delight">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- HTMX CDN -->
    <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Styles / Scripts -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

    <!-- Alpine.js Cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Dynamic Title Script -->
    <script>
        function updateTitle(currentView) {
            const appName = '{{ config('app.name', 'Delight') }}';

            if (currentView === 'dashboard') {
                document.title = `Dashboard - ${appName}`;
            } else if (currentView === 'logs') {
                document.title = `History - ${appName}`;
            } else if (currentView === 'create') {
                document.title = `Log Bible Reading - ${appName}`;
            } else {
                document.title = appName;
            }
        }

        // Set initial title on page load
        document.addEventListener('DOMContentLoaded', () => {
            const initialView = '{{ request()->routeIs("logs.create") ? "create" : (request()->routeIs("logs.*") ? "logs" : "dashboard") }}';
            updateTitle(initialView);
        });
    </script>
</head>

<body class="bg-[#F5F7FA] dark:bg-gray-900 text-gray-600 min-h-screen font-sans antialiased transition-colors">
    <div class="flex h-screen" x-data="{
        currentView: '{{ request()->routeIs('logs.create') ? 'create' : (request()->routeIs('logs.*') ? 'logs' : 'dashboard') }}',
        previousView: 'dashboard',
        init() {
            this.$watch('currentView', (value) => {
                updateTitle(value);
            });
        }
    }">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-col w-48 xl:w-64 bg-white dark:bg-gray-800 border-r border-[#D1D7E0] dark:border-gray-700">
            <!-- Logo Section -->
            <div class="px-6 lg:px-4 xl:px-6 py-3 xl:py-4 border-b border-[#D1D7E0] dark:border-gray-700">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center text-xl lg:text-lg xl:text-xl font-semibold text-[#4A5568] dark:text-gray-200 hover:text-primary-500 dark:hover:text-primary-500 leading-[1.5]">
                    <div class="w-8 h-9 rounded-lg flex items-center justify-center mr-3">
                        <img
                            src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                            srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                            alt="{{ config('app.name') }} Logo"
                            class="w-full h-full object-contain" />
                    </div>
                    <span>{{ config('app.name') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-2 lg:px-1 xl:px-2 pt-6 space-y-1">
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    @click="previousView = currentView; currentView = 'dashboard'"
                    :class="currentView === 'dashboard' ? 'bg-primary-500 text-white' : 'text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500'"
                    class="group flex items-center px-2 py-2 text-base lg:text-sm xl:text-base font-medium rounded-md transition-colors leading-[1.5] w-full text-left">
                    <svg class="w-5 h-5 lg:w-4 lg:h-4 xl:w-5 xl:h-5 mr-3 lg:mr-2 xl:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </button>

                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'logs'"
                    :class="currentView === 'logs' ? 'bg-primary-500 text-white' : 'text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500'"
                    class="group flex items-center px-2 py-2 text-base lg:text-sm xl:text-base font-medium rounded-md transition-colors leading-[1.5] w-full text-left">
                    <svg class="w-5 h-5 lg:w-4 lg:h-4 xl:w-5 xl:h-5 mr-3 lg:mr-2 xl:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z">
                        </path>
                    </svg>
                    History
                </button>

                <button type="button" hx-get="{{ route('logs.create') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'create'"
                    :class="currentView === 'create' ? 'bg-primary-500 text-white' : 'text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500'"
                    class="group flex items-center px-2 py-2 text-base lg:text-sm xl:text-base font-medium rounded-md transition-colors leading-[1.5] w-full text-left">
                    <svg class="w-5 h-5 lg:w-4 lg:h-4 xl:w-5 xl:h-5 mr-3 lg:mr-2 xl:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Log Bible Reading
                </button>

            </nav>

            <!-- User Profile Section - Enhanced Design (No Pro Features) -->
            <div class="flex-shrink-0 px-2 pb-2">
                <!-- User Info Card - Subtle Design -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3 transition-colors border border-gray-100 dark:border-gray-600">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100 truncate leading-[1.5]">
                                {{ auth()->check() ? auth()->user()->name : 'John Doe' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate leading-[1.5]">
                                {{ auth()->check() ? auth()->user()->email : 'john@example.com' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons - Subtle with Blue Accents on Hover -->
                <div class="space-y-1">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <x-ui.button type="submit" variant="ghost"
                            class="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 leading-[1.5]">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:overflow-hidden">
            <!-- Desktop Header with Log Reading Button -->
            <header class="hidden lg:block bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 transition-colors lg:pr-4">
                <div class="container mx-auto px-4 py-3 xl:px-6 xl:py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <h1 id="desktop-page-title" class="text-lg lg:text-xl font-semibold text-[#4A5568] dark:text-gray-200">
                                @yield('page-title', 'Dashboard')
                                <span id="desktop-page-subtitle" class="text-sm text-gray-600 dark:text-gray-400 font-normal ml-3">
                                    @yield('page-subtitle', 'Track your Bible reading progress')
                                </span>
                            </h1>
                        </div>

                        <!-- Primary Action Button - Desktop -->
                        <div class="flex items-center">
                            <x-ui.button
                                variant="accent"
                                size="md"
                                hx-get="{{ route('logs.create') }}"
                                hx-target="#page-container"
                                hx-swap="innerHTML"
                                hx-push-url="true"
                                @click="previousView = currentView; currentView = 'create'"
                                class="!px-4 !py-2 !my-0 !h-9">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Log Bible Reading
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content (includes mobile header that scrolls) -->
            <main class="flex-1 lg:overflow-y-auto">
                <!-- Mobile Header (inside scrollable area) -->
                <header class="lg:hidden bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 px-4 py-3 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center">
                                <img
                                    src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                                    srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                                    alt="{{ config('app.name') }} Logo"
                                    class="w-full h-full object-contain" />
                            </div>
                            <h1 id="mobile-page-title" class="text-lg sm:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                                {{ config('app.name') }}
                            </h1>
                        </div>

                        <!-- Mobile User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open"
                                class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg border border-[#D1D7E0] dark:border-gray-700 py-1 z-50 transition-colors">
                                <div class="px-4 py-2 border-b border-[#D1D7E0] dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ auth()->check() ? auth()->user()->name : 'John Doe' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->check() ? auth()->user()->email : 'john@example.com' }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <div id="page-container" class="lg:flex lg:h-full container mx-auto">
                    @hasSection('sidebar')
                    <!-- Main Content (70% on desktop when sidebar present) -->
                    <div class="lg:flex-1 lg:max-w-[70%] p-4 lg:p-4 xl:p-6 pb-5 md:pb-20 lg:pb-4 xl:pb-6">
                        @yield('content')
                    </div>

                    <!-- Sidebar ntent (30% on desktop) -->
                    <div class="hidden lg:block lg:w-[30%] lg:min-w-[300px] bg-white border-l border-gray-200 p-6">
                        @yield('sidebar')
                    </div>
                    @else
                    <!-- Full-width Content when no sidebar is defined -->
                    <div class="flex-1 p-4 xl:p-6 pb-5 md:pb-20 lg:pb-6">
                        @yield('content')
                    </div>
                    @endif
                </div>
            </main>
        </div>
        <!-- Mobile Bottom Navigation -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-[#D1D7E0] dark:border-gray-700 px-4 py-2 z-40 h-20 transition-colors">
            <div class="flex justify-around">
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    @click="previousView = currentView; currentView = 'dashboard'"
                    :class="currentView === 'dashboard' ? 'text-primary-500' : 'text-gray-500 dark:text-gray-400 hover:text-primary-500'"
                    class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center transition-colors leading-[1.5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Dashboard</span>
                </button>

                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'logs'"
                    :class="currentView === 'logs' ? 'text-primary-500' : 'text-gray-500 dark:text-gray-400 hover:text-primary-500'"
                    class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center transition-colors leading-[1.5]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-xs mt-1">History</span>
                </button>
            </div>
        </nav>

        <!-- Floating Action Button - Mobile Only -->
        <button type="button" hx-get="{{ route('logs.create') }}" hx-target="#page-container"
            hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'create'"
            class="lg:hidden fixed bottom-24 right-4 w-14 h-14 bg-accent-500 hover:bg-accent-600 text-white rounded-full flex items-center justify-center z-50 shadow-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>

    </div>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>

</html>