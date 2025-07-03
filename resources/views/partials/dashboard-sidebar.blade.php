<div class="space-y-6"
     hx-trigger="readingLogAdded from:body" 
     hx-get="{{ route('dashboard') }}" 
     hx-target="this" 
     hx-swap="innerHTML"
     hx-select=".space-y-6">

    <!-- Calendar Heat Map (Work in Progress) -->
    <x-ui.card class="bg-yellow-50 border-yellow-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-yellow-800">📅 Reading Calendar</h4>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><strong>Coming Soon:</strong> GitHub-style calendar showing your daily reading consistency!</p>
                    <div class="mt-3 p-3 bg-yellow-100 rounded">
                        <p class="font-medium mb-2 text-xs">Preview:</p>
                        <div class="grid grid-cols-7 gap-1 text-xs">
                            <div class="w-4 h-4 bg-gray-200 rounded"></div>
                            <div class="w-4 h-4 bg-gray-200 rounded"></div>
                            <div class="w-4 h-4 bg-green-200 rounded"></div>
                            <div class="w-4 h-4 bg-green-400 rounded"></div>
                            <div class="w-4 h-4 bg-green-600 rounded"></div>
                            <div class="w-4 h-4 bg-gray-200 rounded"></div>
                            <div class="w-4 h-4 bg-green-200 rounded"></div>
                        </div>
                        <div class="mt-2 text-xs text-yellow-600">
                            <span class="inline-block w-2 h-2 bg-gray-200 rounded mr-1"></span>No reading
                            <span class="inline-block w-2 h-2 bg-green-400 rounded mr-1 ml-2"></span>Reading day
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Recent Readings -->
    @php
        $statisticsService = app(\App\Services\UserStatisticsService::class);
        $stats = $statisticsService->getDashboardStatistics(auth()->user());
    @endphp
    
    @if(!empty($stats['recent_activity']))
    <x-ui.card class="bg-gray-50">
            <h4 class="font-semibold text-gray-700 mb-3">📚 Recent Readings</h4>
            <div class="space-y-3">
                @foreach(array_slice($stats['recent_activity'], 0, 3) as $reading)
                    <div class="p-3 bg-white rounded border border-gray-200">
                        <div class="font-medium text-gray-700 text-sm">{{ $reading['passage_text'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if($reading['days_ago'] === 0)
                                Today
                            @elseif($reading['days_ago'] === 1)
                                Yesterday
                            @else
                                {{ $reading['days_ago'] }} days ago
                            @endif
            </div>
                        @if($reading['notes_text'])
                            <div class="text-xs text-gray-600 mt-2 italic">{{ Str::limit($reading['notes_text'], 80) }}</div>
                        @endif
            </div>
                @endforeach
        </div>
    </x-ui.card>
    @endif
</div> 