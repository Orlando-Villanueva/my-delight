<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bible Habit Builder') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/logo-192.png') }}">
    <meta name="theme-color" content="#3366CC">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- HTMX CDN -->
    <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js"></script>

    <!-- Alpine.js Focus Plugin for Modal Accessibility -->
    <script defer src="https://unpkg.com/@alpinejs/focus@3.13.3/dist/cdn.min.js"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Dynamic Title Script -->
    <script>
        function updateTitle(currentView) {
            const appName = '{{ config('app.name', 'Bible Habit Builder') }}';
            
            if (currentView === 'dashboard') {
                document.title = `Dashboard - ${appName}`;
            } else if (currentView === 'logs') {
                document.title = `History - ${appName}`;
            } else {
                document.title = appName;
            }
        }

        // Set initial title on page load
        document.addEventListener('DOMContentLoaded', () => {
            const initialView = '{{ request()->routeIs('logs.*') ? 'logs' : 'dashboard' }}';
            updateTitle(initialView);
        });
    </script>
</head>

<body class="bg-[#F5F7FA] dark:bg-gray-900 text-gray-600 min-h-screen font-sans antialiased transition-colors">
    <div class="flex h-screen" x-data="{
        currentView: '{{ request()->routeIs('logs.*') ? 'logs' : 'dashboard' }}',
        previousView: 'dashboard',
        modalOpen: false,
        init() {
            this.$watch('currentView', (value) => {
                updateTitle(value);
            });
        }
    }" @keydown.escape.window="modalOpen = false"
        @close-modal.window="modalOpen = false">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-col w-64 bg-white dark:bg-gray-800 border-r border-[#D1D7E0] dark:border-gray-700">
            <!-- Logo Section -->
            <div class="px-6 py-4 border-b border-[#D1D7E0] dark:border-gray-700">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center text-xl font-semibold text-[#4A5568] dark:text-gray-200 hover:text-primary-500 dark:hover:text-primary-500 leading-[1.5]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                        <img 
                            src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                            srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                            alt="Bible Habit Builder Logo" 
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <span>Bible Habit</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-2 pt-6 space-y-1">
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    @click="previousView = currentView; currentView = 'dashboard'"
                    :class="currentView === 'dashboard' ? 'bg-primary-500 text-white' : 'text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500'"
                    class="group flex items-center px-2 py-2 text-base font-medium rounded-md transition-colors leading-[1.5] w-full text-left">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </button>

                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'logs'"
                    :class="currentView === 'logs' ? 'bg-primary-500 text-white' : 'text-[#4A5568] dark:text-gray-300 hover:bg-[#F5F7FA] dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500'"
                    class="group flex items-center px-2 py-2 text-base font-medium rounded-md transition-colors leading-[1.5] w-full text-left">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z">
                        </path>
                    </svg>
                    History
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
                    <x-ui.button variant="ghost" 
                        class="w-full justify-start text-base text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-primary-500 dark:hover:text-primary-500 leading-[1.5]">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </x-ui.button>
                    
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
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 px-4 py-3 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center">
                            <img 
                                src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                                srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                                alt="Bible Habit Builder Logo" 
                                class="w-full h-full object-contain"
                            />
                        </div>
                        <h1 id="mobile-page-title" class="text-lg sm:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                            Bible Habit
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
                                    {{ auth()->check() ? auth()->user()->name : 'John Doe' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
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

            <!-- Desktop Header with Log Reading Button -->
            <header class="hidden lg:block bg-white dark:bg-gray-800 border-b border-[#D1D7E0] dark:border-gray-700 px-6 py-4 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 id="desktop-page-title" class="text-2xl lg:text-[32px] font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p id="desktop-page-subtitle" class="text-base text-gray-600 dark:text-gray-400 mt-1 leading-[1.5]">
                            @yield('page-subtitle', 'Track your Bible reading progress')
                        </p>
                    </div>

                    <!-- Primary Action Button - Desktop -->
                    <div class="flex items-center space-x-3">
                        <x-ui.button 
                            variant="primary"
                            size="default"
                            hx-get="{{ route('logs.create') }}"
                            hx-target="#reading-log-modal-content" 
                            hx-swap="innerHTML" 
                            hx-indicator="#modal-loading"
                            @click="modalOpen = true"
                            class="!px-6 !py-2 !text-base"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Log Reading
                        </x-ui.button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div id="page-container" class="lg:flex lg:h-full">
                    @hasSection('sidebar')
                        <!-- Main Content (70% on desktop when sidebar present) -->
                        <div class="lg:flex-1 lg:max-w-[70%] p-4 lg:p-6">
                            @yield('content')
                        </div>

                        <!-- Sidebar Content (30% on desktop) -->
                        <div class="hidden lg:block lg:w-[30%] lg:min-w-[300px] bg-white border-l border-gray-200 p-6">
                            @yield('sidebar')
                        </div>
                    @else
                        <!-- Full-width Content when no sidebar is defined -->
                        <div class="flex-1 p-4 lg:p-6">
                            @yield('content')
                        </div>
                    @endif
                </div>
            </main>
        </div>
        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-[#D1D7E0] dark:border-gray-700 px-4 py-2 z-40 h-20 transition-colors">
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
        <button type="button" hx-get="{{ route('logs.create') }}" hx-target="#reading-log-modal-content"
            hx-swap="innerHTML" hx-indicator="#modal-loading" @click="modalOpen = true"
                            class="lg:hidden fixed bottom-24 right-4 w-14 h-14 bg-primary-500 hover:bg-primary-600 text-white rounded-full flex items-center justify-center z-50 shadow-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>

        <!-- Reading Log Modal / Slide-over Container -->
        <!-- Modal Backdrop -->
        <div x-show="modalOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-black/40 z-40"
            @click="modalOpen = false">
        </div>

        <!-- Modal / Slide-over Panel -->
        <aside x-show="modalOpen" x-cloak x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-150" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white dark:bg-gray-800 shadow-xl z-50 overflow-y-auto"
            x-trap.inert.noscroll="modalOpen" role="dialog" aria-modal="true" aria-labelledby="modal-title"
            aria-describedby="modal-description">
            <div id="reading-log-modal-content" class="p-6">
                <!-- HTMX will inject the form here -->

                <!-- Loading Indicator (shown during HTMX requests) -->
                <div id="modal-loading" class="htmx-indicator flex items-center justify-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Loading form...</span>
                </div>
            </div>
        </aside>
    </div>
</body>

</html>
