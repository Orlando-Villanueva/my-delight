@props([
    'elevated' => false
])

@php
    $classes = 'card';
    if ($elevated) {
        $classes .= ' card-elevated';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div> 