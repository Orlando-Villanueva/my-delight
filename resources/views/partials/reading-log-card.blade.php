{{-- Individual Reading Log Item --}}
<div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
    <div class="flex justify-between items-start">
        {{-- Main Reading Info --}}
        <div class="flex-1">
            <div class="flex items-center space-x-3 mb-2">
                {{-- Bible Icon --}}
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>

                {{-- Passage Text --}}
                <h3 class="text-lg font-semibold text-gray-900">{{ $log->passage_text }}</h3>
            </div>

            {{-- Date and Time Info --}}
            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ $log->date_read->format('M d, Y') }}
                </span>

                <span class="text-gray-400">•</span>

                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $log->created_at->format('g:i A') }}
                </span>

                <span class="text-gray-400">•</span>

                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    @php
                        // Since date_read is cast as date (no time), compare dates properly
                        $readingDate = $log->date_read->startOfDay();
                        $today = now()->startOfDay();
                        $yesterday = now()->subDay()->startOfDay();
                        $daysAgo = $readingDate->diffInDays($today);
                    @endphp
                    @if ($readingDate->equalTo($today))
                        Today
                    @elseif($readingDate->equalTo($yesterday))
                        Yesterday
                    @else
                        {{ $daysAgo }} days ago
                    @endif
                </span>
            </div>

            {{-- Expandable Notes Section --}}
            @if ($log->notes_text)
                <details class="mt-3">
                    <summary
                        class="cursor-pointer text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded px-2 py-1 -mx-2 -my-1">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            View notes
                        </span>
                    </summary>
                    <div class="mt-3 p-3 bg-gray-50 rounded-md border-l-4 border-blue-200">
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $log->notes_text }}</p>
                    </div>
                </details>
            @else
                <div class="mt-3 text-sm text-gray-500 italic">
                    No notes recorded
                </div>
            @endif
        </div>

        {{-- Actions Menu (Future Enhancement) --}}
        <div class="flex-shrink-0 ml-4">
            {{-- Placeholder for future actions like edit/delete --}}
            <div class="w-6 h-6 text-gray-300">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>
