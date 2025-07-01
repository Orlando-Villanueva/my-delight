{{-- Reading Logs Header Out-of-Band Update --}}
{{-- These elements will update the header via HTMX out-of-band swapping --}}

{{-- Mobile Header Update --}}
<h1 id="mobile-page-title" hx-swap-oob="innerHTML" class="text-lg font-semibold text-blue-600">
    Reading History
</h1>

{{-- Desktop Header Updates --}}
<h1 id="desktop-page-title" hx-swap-oob="innerHTML" class="text-2xl font-bold text-gray-700">
    Reading History
</h1>

<p id="desktop-page-subtitle" hx-swap-oob="innerHTML" class="text-sm text-gray-500 mt-1">
    View your Bible reading journey
</p> 