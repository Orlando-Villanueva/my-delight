@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = [
        'primary' => 'bg-primary text-white hover:bg-primary/90 focus:ring-primary',
        'secondary' => 'bg-secondary text-white hover:bg-secondary/90 focus:ring-secondary',
        'accent' => 'bg-accent text-white hover:bg-accent/90 focus:ring-accent',
        'outline' => 'border border-neutral-300 text-neutral-600 hover:bg-neutral-50 focus:ring-primary',
        'ghost' => 'text-neutral-600 hover:bg-neutral-100 focus:ring-neutral-500',
        'danger' => 'bg-error text-white hover:bg-error/90 focus:ring-error',
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm min-h-[36px]',
        'md' => 'px-4 py-2 text-base min-h-[44px]',
        'lg' => 'px-6 py-3 text-lg min-h-[48px]',
        'xl' => 'px-8 py-4 text-xl min-h-[56px]',
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    
    $tag = $href ? 'a' : 'button';
    $elementAttributes = $href ? ['href' => $href] : ['type' => $type];
    
    if ($disabled) {
        $elementAttributes['disabled'] = true;
        if ($href) {
            $elementAttributes['aria-disabled'] = 'true';
            $elementAttributes['tabindex'] = '-1';
        }
    }
@endphp

<{{ $tag }} 
    @foreach($elementAttributes as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="sr-only">Loading...</span>
    @elseif($icon && $iconPosition === 'left')
        <span class="mr-2">
            {{ $icon }}
        </span>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <span class="ml-2">
            {{ $icon }}
        </span>
    @endif
</{{ $tag }}> 