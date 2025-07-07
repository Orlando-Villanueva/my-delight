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
</head>

<body class="bg-gray-50 text-gray-600 min-h-screen font-sans antialiased">
    <div class="flex h-screen" x-data="{
        currentView: '{{ request()->routeIs('logs.*') ? 'logs' : 'dashboard' }}',
        previousView: 'dashboard',
        modalOpen: false
    }" @keydown.escape.window="modalOpen = false"
        @close-modal.window="modalOpen = false">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200">
            <!-- Logo Section -->
            <div class="px-6 py-4 border-b border-gray-200">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center text-xl font-bold text-blue-600 hover:text-blue-700">
                    <img src="{{ asset('images/logo-64.png') }}"
                        srcset="{{ asset('images/logo-64.png') }} 1x, {{ asset('images/logo-64-2x.png') }} 2x"
                        alt="Bible Habit Builder Logo" class="h-10 w-auto mr-3">
                    <span>Bible Habit Builder</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-2">
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    @click="previousView = currentView; currentView = 'dashboard'"
                    :class="currentView === 'dashboard' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg min-h-[44px] w-full text-left">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </button>

                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'logs'"
                    :class="currentView === 'logs' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg min-h-[44px] w-full text-left">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z">
                        </path>
                    </svg>
                    History
                </button>


            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center mb-3">
                    <div
                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">
                            {{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-500 hover:text-gray-600">
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-white border-b border-gray-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-64.png') }}"
                            srcset="{{ asset('images/logo-64.png') }} 1x, {{ asset('images/logo-64-2x.png') }} 2x"
                            alt="Bible Habit Builder Logo" class="h-8 w-auto mr-3">
                        <h1 id="mobile-page-title" class="text-lg font-semibold text-blue-600">
                            @yield('page-title', 'Bible Habit Builder')
                        </h1>
                    </div>

                    <!-- Mobile User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open"
                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-600">
                                    {{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-500 hover:text-gray-600">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Desktop Header with Log Reading Button -->
            <header class="hidden lg:block bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 id="desktop-page-title" class="text-2xl font-bold text-gray-700">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        <p id="desktop-page-subtitle" class="text-sm text-gray-500 mt-1">
                            @yield('page-subtitle', 'Track your Bible reading journey')
                        </p>
                    </div>

                    <!-- Primary Action Button - Desktop -->
                    <div class="flex items-center space-x-4">
                        <!-- Quick Stats Badge -->
                        <div class="hidden xl:flex items-center space-x-4 text-sm">
                            <div class="flex items-center text-gray-500">
                                <span class="text-2xl mr-2">🔥</span>
                                <span class="font-medium">7 day streak</span>
                            </div>
                            <div class="w-px h-6 bg-gray-300"></div>
                        </div>

                        <!-- Log Reading Button (Modal Trigger) -->
                        <button type="button" hx-get="{{ route('logs.create') }}"
                            hx-target="#reading-log-modal-content" hx-swap="innerHTML" hx-indicator="#modal-loading"
                            @click="modalOpen = true" class="btn btn-primary min-h-[44px]">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            <span>Log Reading</span>
                        </button>
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
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-40 h-20">
            <div class="flex justify-around">
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    @click="previousView = currentView; currentView = 'dashboard'"
                    :class="currentView === 'dashboard' ? 'text-blue-600' : 'text-gray-500'"
                    class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Dashboard</span>
                </button>

                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true" @click="previousView = currentView; currentView = 'logs'"
                    :class="currentView === 'logs' ? 'text-blue-600' : 'text-gray-500'"
                    class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center">
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
            class="lg:hidden fixed bottom-24 right-4 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
        </button>

        <!-- Reading Log Modal / Slide-over Container -->
        <!-- Modal Backdrop -->
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-black/40 z-40"
            @click="modalOpen = false">
        </div>

        <!-- Modal / Slide-over Panel -->
        <aside x-show="modalOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-150" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 bottom-0 w-full max-w-lg bg-white shadow-xl z-50 overflow-y-auto"
            x-trap.inert.noscroll="modalOpen" role="dialog" aria-modal="true" aria-labelledby="modal-title"
            aria-describedby="modal-description">
            <div id="reading-log-modal-content" class="p-6">
                <!-- HTMX will inject the form here -->

                <!-- Loading Indicator (shown during HTMX requests) -->
                <div id="modal-loading" class="htmx-indicator flex items-center justify-center h-32">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600">Loading form...</span>
                </div>
            </div>
        </aside>
    </div>
</body>

</html>
