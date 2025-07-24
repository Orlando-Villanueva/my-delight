@props([
    'thisWeekDays' => 0,
    'thisMonthDays' => 0,
    'daysInMonth' => 30,
    'totalChapters' => 0,
    'bibleProgress' => 0
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-full transition-colors rounded-lg']) }}>
    <div class="p-6 lg:p-4 xl:p-6 h-full flex items-center">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 w-full">
            <!-- This Week Progress -->
            <div class="flex flex-col items-center text-center">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 mb-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
                    {{ $thisWeekDays }}/7
                </div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">This Week</div>
            </div>

            <!-- This Month Progress -->
            <div class="flex flex-col items-center text-center">
                <div class="p-3 rounded-lg bg-[#3366CC]/10 dark:bg-[#3366CC]/20 mb-3">
                    <svg class="w-5 h-5 text-[#3366CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
                    {{ $thisMonthDays }}/{{ $daysInMonth }}
                </div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">This Month</div>
            </div>

            <!-- Total Chapters -->
            <div class="flex flex-col items-center text-center">
                <div class="p-3 rounded-lg bg-purple-100 dark:bg-purple-900/30 mb-3">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
                    {{ $totalChapters }}
                </div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">Total Chapters</div>
            </div>

            <!-- Bible Progress -->
            <div class="flex flex-col items-center text-center">
                <div class="p-3 rounded-lg bg-[#FF9933]/10 dark:bg-[#FF9933]/20 mb-3">
                    <svg class="w-5 h-5 text-[#FF9933]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="text-xl lg:text-2xl font-bold text-[#4A5568] dark:text-gray-200 mb-1 leading-[1.5]">
                    {{ number_format($bibleProgress, 2) }}%
                </div>
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-[1.5]">Bible Progress</div>
            </div>
        </div>
    </div>
</div> 