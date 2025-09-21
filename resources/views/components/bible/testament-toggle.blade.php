@props([
    'id' => 'testament-toggle'
])

@php
    // Get server-side testament preference for initial state
    $currentTestament = session('testament_preference', 'Old');
@endphp

<div {{ $attributes->merge(['class' => 'flex bg-[#F5F7FA] dark:bg-gray-700 rounded-lg p-1']) }} id="{{ $id }}">
    <button type="button"
            x-on:click="activeTestament = 'Old'"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'Old', 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'Old' }"
            class="px-2 py-1 text-xs font-medium transition-all leading-[1.5] rounded">
        Old
    </button>
    <button type="button"
            x-on:click="activeTestament = 'New'"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'New', 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'New' }"
            class="px-2 py-1 text-xs font-medium transition-all leading-[1.5] rounded">
        New
    </button>
</div> 