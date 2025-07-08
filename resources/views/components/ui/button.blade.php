@props([
    'variant' => 'primary',
    'size' => 'default',
    'type' => 'button',
    'href' => null,
    'disabled' => false
])

@php
    $baseClasses = 'btn';
    
    // Variant classes - using design system classes from app.css
    $variantClasses = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        'link' => 'text-primary underline-offset-4 hover:underline p-0 h-auto min-h-0'
    ];
    
    // Size classes
    $sizeClasses = [
        'default' => '', // Base btn class already has default sizing
        'sm' => 'text-xs px-3 min-h-8',
        'lg' => 'text-base px-8 min-h-12',
        'icon' => 'h-10 w-10 p-0'
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);
    
    // Add size classes (except for link variant which has custom sizing)
    if ($variant !== 'link') {
        $classes .= ' ' . ($sizeClasses[$size] ?? '');
    }
    
    $tag = $href ? 'a' : 'button';
    $elementAttributes = $href ? ['href' => $href] : ['type' => $type];
    
    if ($disabled) {
        $elementAttributes['disabled'] = true;
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<{{ $tag }} 
    @foreach($elementAttributes as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</{{ $tag }}> 