{{-- Mobile Bottom Navigation Bar Component --}}
{{-- Flowbite-based application bar with floating action button --}}

<div class="fixed z-50 w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600 lg:hidden">
    <div class="grid h-full max-w-lg grid-cols-3 mx-auto">
        <!-- Dashboard Button (Left) -->
        <button type="button"
            hx-get="{{ route('dashboard') }}"
            hx-target="#page-container"
            hx-swap="innerHTML"
            hx-push-url="true"
            class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                aria-hidden="true"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
            <span class="sr-only">Dashboard</span>
        </button>

        <!-- Log Reading FAB (Center) -->
        <div class="flex items-center justify-center">
            <button type="button"
                hx-get="{{ route('logs.create') }}"
                hx-target="#page-container"
                hx-swap="innerHTML"
                hx-push-url="true"
                class="inline-flex items-center justify-center w-10 h-10 font-medium bg-accent-500 rounded-full hover:bg-accent-600 group focus:ring-4 focus:ring-accent-300 focus:outline-none dark:focus:ring-accent-800">
                <svg class="w-4 h-4 text-white"
                    aria-hidden="true"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="sr-only">Log Reading</span>
            </button>
        </div>

        <!-- History Button (Right) -->
        <button type="button"
            hx-get="{{ route('logs.index') }}"
            hx-target="#page-container"
            hx-swap="innerHTML"
            hx-push-url="true"
            class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
            <svg class="w-5 h-5 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500"
                aria-hidden="true"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
            </svg>
            <span class="sr-only">History</span>
        </button>
    </div>
</div>
