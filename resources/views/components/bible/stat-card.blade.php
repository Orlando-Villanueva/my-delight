@props([
    'title',
    'value',
    'unit' => '',
    'subtitle' => '',
    'icon' => 'chart',
    'color' => 'blue',
    'compact' => false,
    'trending' => null // 'up', 'down', null
])

@php
    // Color variations
    $colorClasses = match($color) {
        'orange' => 'bg-orange-50 border-orange-200 text-orange-600',
        'success' => 'bg-success-50 border-success-200 text-success-600',
        'purple' => 'bg-purple-50 border-purple-200 text-purple-600',
        'gray' => 'bg-gray-50 border-gray-200 text-gray-600',
        default => 'bg-primary-50 border-primary-200 text-primary-600'
    };
    
    // Size adjustments
    $cardClasses = $compact 
        ? 'p-3' 
        : 'p-4';
        
    $valueSize = $compact 
        ? 'text-xl' 
        : 'text-2xl';
        
    $titleSize = $compact 
        ? 'text-xs' 
        : 'text-sm';
        
    $iconSize = $compact 
        ? 'w-4 h-4' 
        : 'w-5 h-5';
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-lg border hover:shadow-sm transition-all duration-200 $cardClasses"]) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            {{-- Title --}}
            <div class="flex items-center space-x-2 mb-2">
                {{-- Icon --}}
                <div class="flex-shrink-0 p-1 rounded-md {{ $colorClasses }}">
                    @if($icon === 'fire')
                        <svg class="{{ $iconSize }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($icon === 'book')
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @elseif($icon === 'check-circle')
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @elseif($icon === 'calendar')
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    @elseif($icon === 'trending-up')
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    @elseif($icon === 'users')
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    @else
                        {{-- Default chart icon --}}
                        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    @endif
                </div>
                
                <h3 class="{{ $titleSize }} font-medium text-gray-700">{{ $title }}</h3>
                
                {{-- Trending indicator --}}
                @if($trending)
                    <div class="flex-shrink-0">
                        @if($trending === 'up')
                            <svg class="w-3 h-3 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        @elseif($trending === 'down')
                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        @endif
                    </div>
                @endif
            </div>
            
            {{-- Value --}}
            <div class="mb-1">
                <span class="{{ $valueSize }} font-bold text-gray-900">{{ number_format($value) }}</span>
                @if($unit)
                    <span class="text-sm text-gray-500 ml-1">{{ $unit }}</span>
                @endif
            </div>
            
            {{-- Subtitle --}}
            @if($subtitle ?? $slot ?? false)
                <div class="text-xs text-gray-500">
                    {{ $subtitle ?? $slot }}
                </div>
            @endif
        </div>
    </div>
</div> 