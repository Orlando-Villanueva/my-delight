{{-- Header Out-of-Band Updates Component --}}
{{-- This component updates page headers via HTMX out-of-band swapping --}}
{{-- Usage: @include('partials.header-update', ['title' => 'Page Title', 'subtitle' => 'Page subtitle']) --}}

<h1 id="mobile-page-title" hx-swap-oob="innerHTML" class="text-lg font-semibold text-blue-600">
    {{ $title }}
</h1>

<h1 id="desktop-page-title" hx-swap-oob="innerHTML" class="text-2xl font-bold text-gray-700">
    {{ $title }}
</h1>

<p id="desktop-page-subtitle" hx-swap-oob="innerHTML" class="text-sm text-gray-500 mt-1">
    {{ $subtitle }}
</p> 