{{-- Filter Controls and Reading Log Content --}}
{{-- This partial is loaded via HTMX to update both filters and content together --}}

{{-- Filter Controls --}}
<div class="mb-6">
    {{-- Desktop: Button Group --}}
    <div class="hidden md:flex space-x-2" id="filter-buttons">
        <span class="text-sm font-medium text-gray-700 self-center mr-4">Show readings from:</span>
        
        <button hx-get="{{ route('logs.index', ['filter' => '7']) }}" 
                hx-target="#reading-content" 
                hx-swap="innerHTML"
                hx-indicator="#loading"
                onclick="updateActiveFilter(this, '7')"
                data-filter="7"
                class="px-4 py-2 text-sm font-medium rounded-md border {{ $filter === '7' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
            Last 7 days
        </button>
        
        <button hx-get="{{ route('logs.index', ['filter' => '30']) }}" 
                hx-target="#reading-content" 
                hx-swap="innerHTML"
                hx-indicator="#loading"
                onclick="updateActiveFilter(this, '30')"
                data-filter="30"
                class="px-4 py-2 text-sm font-medium rounded-md border {{ $filter === '30' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
            Last 30 days
        </button>
        
        <button hx-get="{{ route('logs.index', ['filter' => '90']) }}" 
                hx-target="#reading-content" 
                hx-swap="innerHTML"
                hx-indicator="#loading"
                onclick="updateActiveFilter(this, '90')"
                data-filter="90"
                class="px-4 py-2 text-sm font-medium rounded-md border {{ $filter === '90' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
            Last 90 days
        </button>
        
        <button hx-get="{{ route('logs.index', ['filter' => 'all']) }}" 
                hx-target="#reading-content" 
                hx-swap="innerHTML"
                hx-indicator="#loading"
                onclick="updateActiveFilter(this, 'all')"
                data-filter="all"
                class="px-4 py-2 text-sm font-medium rounded-md border {{ $filter === 'all' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
            All time
        </button>
    </div>
    
    {{-- Mobile: Dropdown --}}
    <div class="md:hidden">
        <label for="filter-select" class="block text-sm font-medium text-gray-700 mb-2">Show readings from:</label>
        <select id="filter-select" 
                hx-get="{{ route('logs.index') }}" 
                hx-target="#reading-content" 
                hx-swap="innerHTML"
                hx-include="this"
                hx-indicator="#loading"
                name="filter"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="7" {{ $filter === '7' ? 'selected' : '' }}>Last 7 days</option>
            <option value="30" {{ $filter === '30' ? 'selected' : '' }}>Last 30 days</option>
            <option value="90" {{ $filter === '90' ? 'selected' : '' }}>Last 90 days</option>
            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All time</option>
        </select>
    </div>
</div>

{{-- Reading Log Content Container --}}
<div id="reading-content">
    @include('partials.reading-log-list', compact('logs', 'filter'))
</div>

<script>
function updateActiveFilter(clickedButton, filterValue) {
    // Update desktop buttons
    const buttons = document.querySelectorAll('#filter-buttons button[data-filter]');
    buttons.forEach(button => {
        if (button.dataset.filter === filterValue) {
            button.className = 'px-4 py-2 text-sm font-medium rounded-md border bg-blue-600 text-white border-blue-600';
        } else {
            button.className = 'px-4 py-2 text-sm font-medium rounded-md border bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
        }
    });
    
    // Update mobile dropdown
    const select = document.getElementById('filter-select');
    if (select) {
        select.value = filterValue;
    }
}
</script> 