{{-- Reading Log Items Partial - Used for infinite scroll --}}
{{-- This partial renders individual log items and the intersection observer sentinel --}}

<div class="space-y-4">
    @foreach ($logs as $log)
        <x-bible.reading-log-card :log="$log" />
    @endforeach
</div>

{{-- Intersection Observer Sentinel for Infinite Scroll (Mobile Only) --}}
@if ($logs->hasMorePages())
    <div hx-get="{{ $logs->nextPageUrl() }}&filter={{ $filter }}" hx-trigger="intersect once" hx-swap="outerHTML"
        hx-indicator=".htmx-indicator" class="absolute inset-x-0 bottom-0 h-1 md:hidden flex justify-center">
        {{-- Loading indicator that shows during fetch --}}
        <div class="htmx-indicator flex items-center space-x-2 text-gray-500 translate-y-full py-2">
            <div class="animate-spin h-5 w-5 border-2 border-blue-600 border-t-transparent rounded-full"></div>
            <span class="text-sm">Loading more readings...</span>
        </div>
    </div>
@endif

{{-- Desktop HTMX Pagination (hidden on mobile) --}}
@if ($logs->hasPages())
    <div class="hidden md:block mt-8">
        <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
            <div class="-mt-px flex w-0 flex-1">
                @if ($logs->onFirstPage())
                    <span
                        class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-300 cursor-not-allowed">
                        <svg class="mr-3 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1L4.66 9.25h12.59A.75.75 0 0118 10z"
                                clip-rule="evenodd" />
                        </svg>
                        Previous
                    </span>
                @else
                    <button type="button" hx-get="{{ $logs->previousPageUrl() }}&filter={{ $filter }}"
                        hx-target="#reading-content" hx-swap="innerHTML" hx-indicator="#loading"
                        class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        <svg class="mr-3 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1L4.66 9.25h12.59A.75.75 0 0118 10z"
                                clip-rule="evenodd" />
                        </svg>
                        Previous
                    </button>
                @endif
            </div>

            <div class="hidden md:-mt-px md:flex">
                @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if ($page == $logs->currentPage())
                        <span
                            class="inline-flex items-center border-t-2 border-blue-500 px-4 pt-4 text-sm font-medium text-blue-600">
                            {{ $page }}
                        </span>
                    @else
                        <button type="button" hx-get="{{ $url }}&filter={{ $filter }}"
                            hx-target="#reading-content" hx-swap="innerHTML" hx-indicator="#loading"
                            class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="-mt-px flex w-0 flex-1 justify-end">
                @if ($logs->hasMorePages())
                    <button type="button" hx-get="{{ $logs->nextPageUrl() }}&filter={{ $filter }}"
                        hx-target="#reading-content" hx-swap="innerHTML" hx-indicator="#loading"
                        class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                        Next
                        <svg class="ml-3 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <span
                        class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-300 cursor-not-allowed">
                        Next
                        <svg class="ml-3 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    </div>
@endif
