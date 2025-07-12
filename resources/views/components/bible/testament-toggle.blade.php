@props([
    'id' => 'testament-toggle'
])

@php
    // Get server-side testament preference
    $currentTestament = session('testament_preference', 'Old');
@endphp

<div {{ $attributes->merge(['class' => 'flex bg-[#F5F7FA] dark:bg-gray-700 rounded-lg p-1']) }} id="{{ $id }}">
    <button x-on:click="activeTestament = 'Old'"
            hx-post="{{ route('preferences.testament') }}"
            hx-vals='{"testament": "Old"}'
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
            hx-swap="none"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'Old', 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'Old' }"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded {{ $currentTestament === 'Old' ? 'bg-[#3366CC] text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600' }}">
        Old
    </button>
    <button x-on:click="activeTestament = 'New'"
            hx-post="{{ route('preferences.testament') }}"
            hx-vals='{"testament": "New"}'
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
            hx-swap="none"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'New', 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600': activeTestament !== 'New' }"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded {{ $currentTestament === 'New' ? 'bg-[#3366CC] text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-[#3366CC] hover:bg-white dark:hover:bg-gray-600' }}">
        New
    </button>
</div> 