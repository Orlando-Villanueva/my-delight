@props([
    'testament' => 'Old'
])

{{-- Data is now provided by the component class through dependency injection --}}

<x-ui.card {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 transition-colors shadow-lg']) }}>
    <div class="p-4">
        <div x-data="bookProgressComponent('{{ $testament }}', @js($oldData), @js($newData))">
            <!-- Header with Title and Testament Toggle -->
            <div class="flex items-start justify-between mb-6">
                <h3 class="text-lg lg:text-xl font-semibold text-[#4A5568] dark:text-gray-200 leading-[1.5]">
                    Bible Reading Progress
                </h3>
                
                <!-- Testament Toggle -->
                <x-bible.testament-toggle 
                    id="book-grid-testament"
                    class="ml-4 flex-shrink-0" 
                />
            </div>

            <!-- Testament Content (Dynamic) -->
            @include('partials.book-progress-content')
        </div>
    </div>
</x-ui.card>

<script>
    /**
     * Book Progress Component - Client-side Testament Switching
     * All data loaded upfront, no server requests needed for switching
     */
    function bookProgressComponent(serverDefault, oldData, newData) {
        return {
            // State - Use server preference (from session)
            activeTestament: serverDefault,
            oldData: oldData,
            newData: newData,

            // Computed property for current testament data
            get currentData() {
                const data = this.activeTestament === 'Old' ? this.oldData : this.newData;
                return data || { processed_books: [], testament_progress: 0, completed_books: 0, in_progress_books: 0, not_started_books: 0 };
            },

            // Get CSS classes for book status
            getBookStatusClasses(status) {
                switch(status) {
                    case 'completed':
                        return 'bg-success-500 text-white border-success-500 dark:bg-success-600 dark:border-success-600';
                    case 'in-progress':
                        return 'bg-primary-500 text-white border-primary-500';
                    default:
                        return 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50';
                }
            }
        };
    }
</script> 