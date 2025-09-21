@props([
    'allowYesterday' => false,
    'hasReadYesterday' => false,
    'hasReadToday' => false,
    'currentStreak' => 0
])

<!-- Date Selection: Today or Yesterday -->
<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">When did you read?</label>
    <div class="space-y-3">
        <div class="flex items-center">
            <input type="radio" id="today" name="date_read" value="{{ today()->toDateString() }}"
                {{ old('date_read', today()->toDateString()) == today()->toDateString() ? 'checked' : '' }}
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <label for="today" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                📖 Today ({{ today()->format('M d, Y') }})
            </label>
        </div>

        @if($allowYesterday)
            <div class="flex items-center">
                <input type="radio" id="yesterday" name="date_read" value="{{ today()->subDay()->toDateString() }}"
                    {{ old('date_read') == today()->subDay()->toDateString() ? 'checked' : '' }}
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <label for="yesterday" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) - <span class="text-gray-500 dark:text-gray-400 italic">I forgot to log it</span>
                </label>
            </div>
        @else
            {{-- Show why yesterday is not available --}}
            <div class="flex items-center opacity-50">
                <input type="radio" disabled
                    class="h-4 w-4 text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed">
                <label class="ml-3 block text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed">
                    📅 Yesterday ({{ today()->subDay()->format('M d, Y') }}) -
                    @if(!$hasReadYesterday && $currentStreak > 0 && !$hasReadToday)
                        <span class="italic">Would break your {{ $currentStreak }}-day streak</span>
                    @else
                        <span class="italic">Already logged</span>
                    @endif
                </label>
            </div>
        @endif
    </div>
    <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
        💡 <strong>Grace Period:</strong> You can log today's reading or catch up on yesterday if you forgot.
    </div>
</div>