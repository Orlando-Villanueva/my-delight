@props([
    'variant' => 'default',
    'padding' => true,
    'header' => null,
    'footer' => null,
    'elevated' => false
])

@php
    $baseClasses = 'bg-white border border-neutral-200 rounded-lg';
    $elevationClasses = $elevated ? 'shadow-md hover:shadow-lg transition-shadow' : 'shadow-sm';
    
    $variantClasses = [
        'default' => '',
        'primary' => 'border-primary/20 bg-primary/5',
        'secondary' => 'border-secondary/20 bg-secondary/5',
        'accent' => 'border-accent/20 bg-accent/5',
        'success' => 'border-success/20 bg-success/5',
        'warning' => 'border-warning/20 bg-warning/5',
        'error' => 'border-error/20 bg-error/5',
    ];
    
    $classes = $baseClasses . ' ' . $elevationClasses . ' ' . ($variantClasses[$variant] ?? '');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($header)
        <div class="px-6 py-4 border-b border-neutral-200">
            {{ $header }}
        </div>
    @endif
    
    <div class="{{ $padding ? 'px-6 py-4' : '' }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50">
            {{ $footer }}
        </div>
    @endif
</div> 