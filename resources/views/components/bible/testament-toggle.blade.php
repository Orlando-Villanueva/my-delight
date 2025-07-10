@props([
    'id' => 'testament-toggle',
    'target' => '#book-progress-content', 
    'activeTestament' => 'Old'
])

<div {{ $attributes->merge(['class' => 'flex bg-[#F5F7FA] rounded-lg p-1']) }} id="{{ $id }}">
    <button hx-get="{{ route('dashboard.books', ['testament' => 'Old']) }}"
            hx-target="{{ $target }}"
            hx-swap="innerHTML"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded {{ $activeTestament === 'Old' ? 'bg-[#3366CC] text-white shadow-sm' : 'text-gray-600 hover:text-[#3366CC] hover:bg-white' }}">
        Old
    </button>
    <button hx-get="{{ route('dashboard.books', ['testament' => 'New']) }}"
            hx-target="{{ $target }}"
            hx-swap="innerHTML"
            class="px-3 py-1.5 text-sm font-medium transition-all leading-[1.5] rounded {{ $activeTestament === 'New' ? 'bg-[#3366CC] text-white shadow-sm' : 'text-gray-600 hover:text-[#3366CC] hover:bg-white' }}">
        New
    </button>
</div> 