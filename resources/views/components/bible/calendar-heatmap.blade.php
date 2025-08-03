@props([
    'user' => null,
    'months' => 1,
    'showLegend' => false
])

@php
    $user = $user ?? auth()->user();
    $statisticsService = app(\App\Services\UserStatisticsService::class);
    $calendarData = $statisticsService->getCalendarData($user);
    
    // Get current month data
    $currentDate = now();
    $year = $currentDate->year;
    $month = $currentDate->month;
    $monthName = $currentDate->format('F Y');
    
    // Generate calendar grid for current month
    $firstDay = $currentDate->copy()->startOfMonth();
    $lastDay = $currentDate->copy()->endOfMonth();
    $daysInMonth = $lastDay->day;
    $startingDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday, 6 = Saturday
    
    $calendar = [];
    
    // Add empty cells for days before month starts
    for ($i = 0; $i < $startingDayOfWeek; $i++) {
        $calendar[] = null;
    }
    
    // Add days of the month with reading data
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = $currentDate->copy()->startOfMonth()->addDays($day - 1);
        $dateStr = $date->toDateString();
        
        // Get reading data for this day from the service
        $dayData = $calendarData[$dateStr] ?? null;
        $readingCount = $dayData ? $dayData['reading_count'] : 0;
        $hasReading = $dayData ? $dayData['has_reading'] : false;
        
        $calendar[] = [
            'day' => $day,
            'date' => $date,
            'hasReading' => $hasReading,
            'readingCount' => $readingCount,
            'isToday' => $date->isToday(),
            'dateString' => $dateStr
        ];
    }
    
    // Calculate monthly stats
    $thisMonthReadings = 0;
    foreach ($calendar as $day) {
        if ($day !== null && $day['hasReading']) {
            $thisMonthReadings++;
        }
    }
    
    // Calculate success rate based on days passed in month
    $today = now();
    $daysPassedInMonth = $today->month === $month && $today->year === $year 
        ? min($daysInMonth, $today->day) 
        : $daysInMonth;
    $successRate = $daysPassedInMonth > 0 ? round(($thisMonthReadings / $daysPassedInMonth) * 100) : 0;
@endphp

<x-ui.card {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 h-fit transition-colors shadow-lg']) }}>
    <div class="p-4 lg:p-3 xl:p-4">
        <!-- Header -->
        <div class="pb-3 border-b border-[#D1D7E0] dark:border-gray-600 mb-4 lg:mb-3">
            <h4 class="text-lg lg:text-base xl:text-lg font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">Reading Calendar</h4>
            <p class="text-sm lg:text-xs xl:text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">{{ $monthName }}</p>
        </div>
        
        <!-- Calendar Grid -->
        <div class="space-y-4 lg:space-y-3">
            <div>
                <!-- Day headers -->
                <div class="grid grid-cols-7 gap-1 sm:gap-2 lg:gap-1 mb-2">
                    @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $dayLabel)
                        <div class="text-center text-sm lg:text-xs xl:text-sm font-medium text-gray-500 dark:text-gray-400 py-1 leading-[1.5]">{{ $dayLabel }}</div>
                    @endforeach
                </div>
                
                <!-- Calendar days -->
                <div class="grid grid-cols-7 gap-1 sm:gap-1.5 lg:gap-1">
                    @foreach($calendar as $day)
                        @if($day === null)
                            <div class="aspect-square sm:aspect-[4/3] lg:aspect-square"></div>
                        @else
                            @php
                                // Intensity based on reading count (chapters read)
                                $count = $day['readingCount'];
                                if ($count === 0) {
                                    $intensityClass = 'bg-[#F5F7FA] dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-[#E2E8F0] dark:hover:bg-gray-600 border border-[#D1D7E0] dark:border-gray-600';
                                } elseif ($count === 1) {
                                    $intensityClass = 'bg-[#66CC99]/20 dark:bg-[#66CC99]/30 text-gray-800 dark:text-gray-200 hover:bg-[#66CC99]/30 dark:hover:bg-[#66CC99]/40';
                                } elseif ($count === 2) {
                                    $intensityClass = 'bg-[#66CC99]/40 dark:bg-[#66CC99]/50 text-gray-800 dark:text-white hover:bg-[#66CC99]/50 dark:hover:bg-[#66CC99]/60';
                                } elseif ($count === 3) {
                                    $intensityClass = 'bg-[#66CC99]/60 dark:bg-[#66CC99]/70 text-white hover:bg-[#66CC99]/70 dark:hover:bg-[#66CC99]/80';
                                } elseif ($count === 4) {
                                    $intensityClass = 'bg-[#66CC99]/75 dark:bg-[#66CC99]/85 text-white hover:bg-[#66CC99]/85 dark:hover:bg-[#66CC99]/90';
                                } elseif ($count === 5) {
                                    $intensityClass = 'bg-[#66CC99]/90 dark:bg-[#66CC99] text-white hover:bg-[#66CC99] dark:hover:bg-[#5AB88A]';
                                } elseif ($count === 6) {
                                    $intensityClass = 'bg-[#66CC99] dark:bg-[#5AB88A] text-white hover:bg-[#5AB88A] dark:hover:bg-[#4DA67A]';
                                } else { // 7+
                                    $intensityClass = 'bg-[#5AB88A] dark:bg-[#4DA67A] text-white hover:bg-[#4DA67A] dark:hover:bg-[#3E8E41]';
                                }
                                
                                // Today indicator
                                if ($day['isToday']) {
                                    $intensityClass .= ' ring-2 ring-[#3366CC] dark:ring-blue-400 ring-offset-1 dark:ring-offset-gray-800';
                                }
                            @endphp
                            
                            <div class="aspect-square sm:aspect-[4/3] lg:aspect-square flex items-center justify-center text-sm sm:text-sm lg:text-xs xl:text-sm rounded-full transition-all duration-200 cursor-pointer leading-[1.5] {{ $intensityClass }}"
                                 title="{{ $day['date']->format('F j, Y') }}{{ $day['hasReading'] ? ' - ' . $day['readingCount'] . ' chapter' . ($day['readingCount'] !== 1 ? 's' : '') . ' read' : ' - No reading' }}">
                                {{ $day['day'] }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Monthly Stats -->
            <div class="pt-3 lg:pt-2 border-t border-[#D1D7E0] dark:border-gray-600">
                <div class="grid grid-cols-2 gap-4 lg:gap-2 text-center">
                    <div>
                        <div class="text-lg lg:text-base xl:text-lg font-bold text-[#66CC99] dark:text-[#66CC99] leading-[1.5]">{{ $thisMonthReadings }}</div>
                        <div class="text-sm lg:text-xs xl:text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">This Month</div>
                    </div>
                    <div>
                        <div class="text-lg lg:text-base xl:text-lg font-bold text-[#3366CC] dark:text-blue-400 leading-[1.5]">{{ $successRate }}%</div>
                        <div class="text-sm lg:text-xs xl:text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Success Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ui.card> 