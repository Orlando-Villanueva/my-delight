@props([
    'elevated' => false
])

@php
    $classes = 'card';
    if ($elevated) {
        $classes .= ' shadow-md';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div> 