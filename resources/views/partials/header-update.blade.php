{{-- Header Out-of-Band Updates Component --}}
{{-- This component updates page headers via HTMX out-of-band swapping --}}
{{-- Usage: @include('partials.header-update', ['title' => 'Page Title', 'subtitle' => 'Page subtitle']) --}}

<h1 id="mobile-page-title" hx-swap-oob="innerHTML" class="text-lg sm:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
    {{ config('app.name') }}
</h1>

<div hx-swap-oob="innerHTML:#desktop-page-title">
    {{ $title }}
    <span id="desktop-page-subtitle" class="text-sm text-gray-600 dark:text-gray-400 font-normal ml-3">
        {{ $subtitle }}
    </span>
</div> 