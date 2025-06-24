@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'disabled' => false
])

@php
    $baseClasses = 'btn';
    
    $variantClasses = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
    ];
    
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']);
    
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