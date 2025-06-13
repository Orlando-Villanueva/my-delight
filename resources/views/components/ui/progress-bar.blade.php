@props([
    'value' => 0,
    'max' => 100,
    'label' => null,
    'showPercentage' => true,
    'size' => 'default',
    'variant' => 'primary'
])

@php
    $percentage = $max > 0 ? min(100, ($value / $max) * 100) : 0;
    
    $sizeClasses = [
        'sm' => 'h-1',
        'default' => 'h-2',
        'lg' => 'h-3',
        'xl' => 'h-4'
    ];
    
    $variantClasses = [
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'accent' => 'bg-accent',
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'error' => 'bg-error'
    ];
    
    $progressHeight = $sizeClasses[$size] ?? $sizeClasses['default'];
    $progressColor = $variantClasses[$variant] ?? $variantClasses['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if($label || $showPercentage)
        <div class="flex justify-between items-center text-sm">
            @if($label)
                <span class="font-medium text-neutral-600">{{ $label }}</span>
            @endif
            @if($showPercentage)
                <span class="text-neutral-500">{{ number_format($percentage, 1) }}%</span>
            @endif
        </div>
    @endif
    
    <div class="w-full bg-neutral-200 rounded-full overflow-hidden {{ $progressHeight }}" role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="{{ $max }}">
        <div 
            class="h-full transition-all duration-300 ease-out {{ $progressColor }}" 
            style="width: {{ $percentage }}%"
            aria-label="{{ $label ? $label . ': ' : '' }}{{ number_format($percentage, 1) }}% complete"
        ></div>
    </div>
    
    @if($value > 0 && $max > 0)
        <div class="text-xs text-neutral-500">
            {{ $value }} of {{ $max }} {{ $label ? strtolower($label) : 'items' }}
        </div>
    @endif
</div> 