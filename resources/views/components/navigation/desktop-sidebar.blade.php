{{-- Desktop Sidebar Navigation Component --}}
{{-- Flowbite-based sidebar with HTMX navigation and hover states only --}}

<aside
    class="hidden lg:flex lg:flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 lg:pt-16">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <!-- Navigation Menu -->
        <ul class="space-y-2 font-medium">
            <li>
                <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container" hx-swap="innerHTML"
                    hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 group transition-colors">
                    <svg class="w-6 h-6 text-gray-600 transition duration-75 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5" />
                    </svg>

                    <span class="ms-3">Dashboard</span>
                </button>
            </li>
            <li>
                <button type="button" hx-get="{{ route('logs.create') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 group transition-colors">
                    <svg class="w-6 h-6 text-gray-600 transition duration-75 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
                    </svg>

                    <span class="ms-3">Log Reading</span>
                </button>
            </li>
            <li>
                <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container"
                    hx-swap="innerHTML" hx-push-url="true"
                    class="w-full flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-primary-50 dark:hover:bg-gray-700 group transition-colors">
                    <svg class="w-6 h-6 text-gray-600 transition duration-75 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
                    </svg>

                    <span class="ms-3">History</span>
                </button>
            </li>
        </ul>
    </div>
</aside>
