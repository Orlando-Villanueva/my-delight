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

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Tailwind CDN (Fallback if Vite is not running) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: '#3366CC',
                            secondary: '#66CC99',
                            accent: '#FF9933',
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- Alpine.js Cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-6">
        <header class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ url('/') }}"
                    class="flex items-center text-2xl font-bold text-primary dark:text-primary hover:opacity-90 transition">
                    <img src="{{ asset('images/logo-header.png') }}"
                        srcset="{{ asset('images/logo-header.png') }} 1x, {{ asset('images/logo-header-2x.png') }} 2x"
                        alt="Bible Habit Builder Logo" class="h-14 w-auto mr-3">
                    <span class="hidden sm:inline">Bible Habit Builder</span>
                </a>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-4 mt-4 sm:mt-0">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition text-sm font-medium">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
                <a href="{{ url('/') }}"
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition text-sm font-medium">
                    Welcome
                </a>

            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer
            class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} Bible Habit Builder. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
