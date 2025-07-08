@props([
    'tag' => 'h3'
])

<{{ $tag }} {{ $attributes->merge(['class' => 'card-title']) }}>
    {{ $slot }}
</{{ $tag }}> 