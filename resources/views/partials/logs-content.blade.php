<div class="max-w-4xl mx-auto pb-20 md:pb-4"
    x-data="{ currentFilter: '{{ $filter }}' }"
    @htmx:before-request="if ($event.detail.elt.dataset.filter) currentFilter = $event.detail.elt.dataset.filter">
    {{-- Filter Controls --}}
    <div class="mb-6">
        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 md:mb-4 md:inline md:mr-4">Show readings from:</span>

        <div class="grid grid-cols-2 gap-2 md:inline-flex md:space-x-2">
            <x-ui.button
                variant="outline"
                size="default"
                hx-get="{{ route('logs.index', ['filter' => '7']) }}"
                hx-target="#reading-content"
                hx-swap="innerHTML"
                hx-indicator="#loading"
                data-filter="7"
                x-bind:class="currentFilter === '7' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'">
                Last 7 days
            </x-ui.button>

            <x-ui.button
                variant="outline"
                size="default"
                hx-get="{{ route('logs.index', ['filter' => '30']) }}"
                hx-target="#reading-content"
                hx-swap="innerHTML"
                hx-indicator="#loading"
                data-filter="30"
                x-bind:class="currentFilter === '30' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'">
                Last 30 days
            </x-ui.button>

            <x-ui.button
                variant="outline"
                size="default"
                hx-get="{{ route('logs.index', ['filter' => '90']) }}"
                hx-target="#reading-content"
                hx-swap="innerHTML"
                hx-indicator="#loading"
                data-filter="90"
                x-bind:class="currentFilter === '90' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'">
                Last 90 days
            </x-ui.button>

            <x-ui.button
                variant="outline"
                size="default"
                hx-get="{{ route('logs.index', ['filter' => 'all']) }}"
                hx-target="#reading-content"
                hx-swap="innerHTML"
                hx-indicator="#loading"
                data-filter="all"
                x-bind:class="currentFilter === 'all' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'">
                All time
            </x-ui.button>
        </div>
    </div>

    {{-- Reading Log Content Container --}}
    <div id="reading-content" class="relative">
        {{-- Loading Indicator - Only covers the logs area --}}
        <div id="loading"
            class="htmx-indicator absolute inset-0 bg-white dark:bg-gray-900 bg-opacity-90 dark:bg-opacity-90 flex items-center justify-center z-10">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Loading readings...</span>
        </div>

        <div id="reading-list-container"
            hx-trigger="readingLogAdded from:body"
            hx-get="{{ route('logs.index', ['filter' => $filter]) }}"
            hx-target="this"
            hx-swap="innerHTML">
            @include('partials.reading-log-list', compact('logs', 'filter'))
        </div>
    </div>
</div>