<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bible Habit Builder') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- HTMX CDN -->
        <script src="https://unpkg.com/htmx.org@1.9.10" integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC" crossorigin="anonymous"></script>
        
        <!-- Alpine.js CDN -->
        <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js Cloak -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-neutral-50 dark:bg-gray-900 text-neutral-600 dark:text-gray-100 min-h-screen font-sans antialiased">
        <div class="flex h-screen">
            <!-- Desktop Sidebar Navigation -->
            <aside class="hidden lg:flex lg:flex-col w-64 bg-white dark:bg-gray-800 border-r border-neutral-300 dark:border-gray-700">
                <!-- Logo Section -->
                <div class="p-6 border-b border-neutral-300 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-primary dark:text-primary hover:opacity-90 transition">
                        Bible Habit Builder
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 p-4 space-y-2">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors min-h-[44px] {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary border-r-2 border-primary' : 'text-neutral-600 hover:bg-neutral-100' }} dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('history') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors min-h-[44px] {{ request()->routeIs('history') ? 'bg-primary/10 text-primary border-r-2 border-primary' : 'text-neutral-600 hover:bg-neutral-100' }} dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                        </svg>
                        History
                    </a>

                    <a href="{{ route('profile') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors min-h-[44px] {{ request()->routeIs('profile*') ? 'bg-primary/10 text-primary border-r-2 border-primary' : 'text-neutral-600 hover:bg-neutral-100' }} dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="p-4 border-t border-neutral-300 dark:border-gray-700">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-600 dark:text-gray-300">{{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                            <p class="text-xs text-neutral-500">{{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-neutral-500 hover:text-neutral-600 dark:hover:text-gray-300 transition">
                            Sign out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Mobile Header -->
                <header class="lg:hidden bg-white dark:bg-gray-800 border-b border-neutral-300 dark:border-gray-700 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <h1 class="text-lg font-semibold text-primary dark:text-primary">
                            @yield('page-title', 'Bible Habit Builder')
                        </h1>
                        
                        <!-- Mobile User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-neutral-300 dark:border-gray-700 py-1 z-50">
                                <div class="px-4 py-2 border-b border-neutral-300 dark:border-gray-700">
                                    <p class="text-sm font-medium text-neutral-600 dark:text-gray-300">{{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                                    <p class="text-xs text-neutral-500">{{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-neutral-500 hover:text-neutral-600 dark:hover:text-gray-300 transition">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Desktop Header with Log Reading Button -->
                <header class="hidden lg:block bg-white dark:bg-gray-800 border-b border-neutral-300 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-neutral-dark dark:text-gray-100">
                                @yield('page-title', 'Dashboard')
                            </h1>
                            <p class="text-sm text-neutral-500 dark:text-gray-400 mt-1">
                                @yield('page-subtitle', 'Track your Bible reading journey')
                            </p>
                        </div>
                        
                        <!-- Primary Action Button - Desktop -->
                        <div class="flex items-center space-x-4">
                            <!-- Quick Stats Badge -->
                            <div class="hidden xl:flex items-center space-x-4 text-sm">
                                <div class="flex items-center text-neutral-500">
                                    <span class="text-2xl mr-2">🔥</span>
                                    <span class="font-medium">7 day streak</span>
                                </div>
                                <div class="w-px h-6 bg-neutral-300"></div>
                            </div>
                            
                            <!-- Log Reading Button -->
                            <a href="{{ route('logs.create') }}" 
                               class="bg-accent hover:bg-accent/90 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 shadow-sm hover:shadow-md transition-all duration-200 min-h-[44px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>Log Reading</span>
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    <div class="lg:flex lg:h-full">
                        <!-- Main Content (70% on desktop) -->
                        <div class="lg:flex-1 lg:max-w-[70%] p-4 lg:p-6">
                            @yield('content')
                        </div>

                        <!-- Sidebar Content (30% on desktop) -->
                        <div class="hidden lg:block lg:w-[30%] lg:min-w-[300px] bg-white dark:bg-gray-800 border-l border-neutral-300 dark:border-gray-700 p-6">
                            @yield('sidebar')
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-neutral-200 dark:border-gray-700 px-4 py-2 z-40">
            <div class="flex justify-around">
                <a href="{{ route('dashboard') }}" 
                   class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center transition-colors {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-neutral-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Dashboard</span>
                </a>

                <a href="{{ route('history') }}" 
                   class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center transition-colors {{ request()->routeIs('history') ? 'text-primary' : 'text-neutral-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs mt-1">History</span>
                </a>

                <a href="{{ route('profile') }}" 
                   class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center transition-colors {{ request()->routeIs('profile*') ? 'text-primary' : 'text-neutral-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs mt-1">Profile</span>
                </a>
            </div>
        </nav>

        <!-- Floating Action Button - Mobile Only -->
        <a href="{{ route('logs.create') }}" 
           class="lg:hidden fixed w-14 h-14 bg-accent hover:bg-accent/90 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center bottom-20 right-4 z-50 group">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </a>
    </body>
</html> 