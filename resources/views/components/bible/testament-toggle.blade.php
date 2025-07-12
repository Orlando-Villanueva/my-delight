@props([
    'id' => 'testament-toggle'
])

<div {{ $attributes->merge(['class' => 'flex bg-[#F5F7FA] rounded-lg p-1']) }} id="{{ $id }}">
    <button x-on:click="activeTestament = 'Old'"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'Old', 'text-gray-600 hover:text-[#3366CC] hover:bg-white': activeTestament !== 'Old' }"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded">
        Old
    </button>
    <button x-on:click="activeTestament = 'New'"
            :class="{ 'bg-[#3366CC] text-white shadow-sm': activeTestament === 'New', 'text-gray-600 hover:text-[#3366CC] hover:bg-white': activeTestament !== 'New' }"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded">
        New
    </button>
</div> 