{{-- Mobile Bottom Navigation Bar Component --}}
{{-- Flowbite-based application bar with floating action button --}}

<div
    class="fixed z-50 w-full h-16 max-w-lg -translate-x-1/2 bg-white/60 backdrop-blur-md border-2  border-gray-200/50 rounded-full bottom-4 left-1/2 dark:bg-gray-700/70 dark:border-gray-600/50 lg:hidden">
    <div class="grid h-full max-w-lg grid-cols-3 mx-auto">
        <!-- Dashboard Button (Left) -->
        <button type="button" hx-get="{{ route('dashboard') }}" hx-target="#page-container" hx-swap="innerHTML"
            hx-push-url="true"
            class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5" />
            </svg>
            <span class="sr-only">Dashboard</span>
        </button>

        <!-- Log Reading FAB (Center) -->
        <div class="flex items-center justify-center">
            <button type="button" hx-get="{{ route('logs.create') }}" hx-target="#page-container" hx-swap="innerHTML"
                hx-push-url="true"
                class="inline-flex items-center justify-center w-10 h-10 font-medium bg-accent-500 rounded-full hover:bg-accent-600 group focus:ring-4 focus:ring-accent-300 focus:outline-none dark:focus:ring-accent-800">
                <svg class="w-4 h-4 text-white" aria-hidden="true" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="sr-only">Log Reading</span>
            </button>
        </div>

        <!-- History Button (Right) -->
        <button type="button" hx-get="{{ route('logs.index') }}" hx-target="#page-container" hx-swap="innerHTML"
            hx-push-url="true"
            class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5" />
            </svg>
            <span class="sr-only">History</span>
        </button>
    </div>
</div>
