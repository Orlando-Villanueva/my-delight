{{-- Header Out-of-Band Updates Component --}}
{{-- This component updates page headers via HTMX out-of-band swapping --}}
{{-- Usage: @include('partials.header-update', ['title' => 'Page Title', 'subtitle' => 'Page subtitle']) --}}

<h1 class="text-lg sm:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
    {{ config('app.name') }}
</h1>

<div>
    {{ $title }}
</div>

<div>
    {{ $subtitle }}
</div> 