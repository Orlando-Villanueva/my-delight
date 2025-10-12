{{-- Desktop Sidebar Navigation Component --}}
{{-- Flowbite-based sidebar with HTMX navigation and hover states only --}}

<aside class="hidden lg:flex lg:flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <!-- Logo Section -->
        <a href="{{ route('dashboard') }}"
            hx-get="{{ route('dashboard') }}"
            hx-target="#page-container"
            hx-push-url="true"
            class="flex items-center ps-2.5 mb-5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center me-3">
                <img
                    src="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }}"
                    srcset="{{ asset('images/logo-64.png') }}?v={{ config('app.asset_version') }} 1x, {{ asset('images/logo-64-2x.png') }}?v={{ config('app.asset_version') }} 2x"
                    alt="{{ config('app.name') }} Logo"
                    class="w-full h-full object-contain" />
            </div>
            <span class="self-center text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>

        <!-- Navigation Menu -->
        <ul class="space-y-2 font-medium">
            <li>
                <button type="button"
                    hx-get="{{ route('dashboard') }}"
                    hx-target="#page-container"
                    hx-swap="innerHTML"
                    hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-500 group transition-colors">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </button>
            </li>
            <li>
                <button type="button"
                    hx-get="{{ route('logs.create') }}"
                    hx-target="#page-container"
                    hx-swap="innerHTML"
                    hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-500 group transition-colors">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="ms-3">Log Reading</span>
                </button>
            </li>
            <li>
                <button type="button"
                    hx-get="{{ route('logs.index') }}"
                    hx-target="#page-container"
                    hx-swap="innerHTML"
                    hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-500 group transition-colors">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                        aria-hidden="true"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                    <span class="ms-3">History</span>
                </button>
            </li>
        </ul>
    </div>
</aside>
