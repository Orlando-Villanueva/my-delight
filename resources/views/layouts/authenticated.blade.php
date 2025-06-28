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
        <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js"></script>
        <!-- HTMX Response Targets Extension for Error Handling -->
        <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/ext/response-targets.js"></script>
        
        <!-- Alpine.js CDN -->
        <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js Cloak -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-600 min-h-screen font-sans antialiased">
        <div class="flex h-screen" x-data="{ 
            currentView: '{{ request()->routeIs('logs.*') ? 'logs' : 'dashboard' }}',
            previousView: 'dashboard'
        }">
            <!-- Desktop Sidebar Navigation -->
            <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200">
                <!-- Logo Section -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 hover:text-blue-700">
                        Bible Habit Builder
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 p-4 space-y-2">
                    <button hx-get="{{ route('dashboard') }}" 
                            hx-target="#main-content" 
                            hx-swap="innerHTML"
                            @click="previousView = currentView; currentView = 'dashboard'"
                            :class="currentView === 'dashboard' ? 'nav-active' : 'text-gray-600 hover:bg-gray-100'"
                            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg min-h-[44px] w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </button>

                    <button hx-get="{{ route('logs.index') }}" 
                            hx-target="#main-content" 
                            hx-swap="innerHTML"
                            @click="previousView = currentView; currentView = 'logs'"
                            :class="currentView === 'logs' ? 'nav-active' : 'text-gray-600 hover:bg-gray-100'"
                            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg min-h-[44px] w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                        </svg>
                        History
                    </button>


                </nav>

                <!-- User Info & Logout -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">{{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
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
                        <h1 class="text-lg font-semibold text-blue-600">
                            @yield('page-title', 'Bible Habit Builder')
                        </h1>
                        
                        <!-- Mobile User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'J' }}
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-600">{{ auth()->check() ? auth()->user()->name : 'John Doe (Preview)' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->check() ? auth()->user()->email : 'john@example.com' }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-500 hover:text-gray-600">
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
                            <h1 class="text-2xl font-bold text-gray-700">
                                @yield('page-title', 'Dashboard')
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
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
                            
                            <!-- Log Reading Button (HTMX Content Loading) -->
                            <button hx-get="{{ route('logs.create') }}" 
                                    hx-target="#main-content" 
                                    hx-swap="innerHTML"
                                    @click="previousView = currentView"
                                    class="btn btn-primary min-h-[44px]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>Log Reading</span>
                            </button>
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
                        <div class="hidden lg:block lg:w-[30%] lg:min-w-[300px] bg-white border-l border-gray-200 p-6">
                            @yield('sidebar')
                        </div>
                    </div>
                </main>
            </div>
            <!-- Mobile Bottom Navigation -->
            <nav class="mobile-nav lg:hidden fixed bottom-0 left-0 right-0 px-4 py-2 z-40">
                <div class="flex justify-around">
                    <button hx-get="{{ route('dashboard') }}" 
                            hx-target="#main-content" 
                            hx-swap="innerHTML"
                            @click="previousView = currentView; currentView = 'dashboard'"
                            :class="currentView === 'dashboard' ? 'text-blue-600' : 'text-gray-500'"
                            class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        <span class="text-xs mt-1">Dashboard</span>
                    </button>

                    <button hx-get="{{ route('logs.index') }}" 
                            hx-target="#main-content" 
                            hx-swap="innerHTML"
                            @click="previousView = currentView; currentView = 'logs'"
                            :class="currentView === 'logs' ? 'text-blue-600' : 'text-gray-500'"
                            class="flex flex-col items-center py-2 px-3 min-w-[44px] min-h-[44px] justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs mt-1">History</span>
                    </button>


                </div>
            </nav>

            <!-- Floating Action Button - Mobile Only -->
            <button hx-get="{{ route('logs.create') }}" 
                    hx-target="#main-content" 
                    hx-swap="innerHTML"
                    @click="previousView = currentView"
                    class="lg:hidden fixed bottom-22 right-4 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </button>
        </div>
    </body>
</html> 