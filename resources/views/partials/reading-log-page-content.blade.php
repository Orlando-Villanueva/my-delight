{{-- Reading Log Page Content Partial --}}
{{-- This partial is loaded via HTMX for seamless navigation --}}

<div class="max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Reading History</h1>
        
        {{-- Back to Dashboard Button --}}
        <button hx-get="{{ route('dashboard') }}" 
                hx-target="#main-content" 
                hx-swap="innerHTML"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            ← Back to Dashboard
        </button>
    </div>
    
    {{-- Loading Indicator --}}
    <div id="loading" class="htmx-indicator">
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading readings...</span>
        </div>
    </div>
    
    {{-- Filters and Content Container --}}
    <div id="filters-and-content">
        @include('partials.reading-log-filters-and-content', compact('logs', 'filter'))
    </div>
</div> 