@props([
    'testament' => 'Old'
])

@php
    $user = auth()->user();
    $bookProgressService = app(\App\Services\BookProgressService::class);
    
    $oldData = $bookProgressService->getTestamentProgress($user, 'Old');
    $newData = $bookProgressService->getTestamentProgress($user, 'New');
    
    $testament = $attributes->get('testament', 'Old'); // Maintain initial testament for Alpine
@endphp

<x-ui.card {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-[#D1D7E0] dark:border-gray-700 transition-colors']) }}>
    <div class="p-6">
        <div x-data="bookProgressComponent('{{ $testament }}')">
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

            <!-- Old Testament Content -->
            <div x-show="activeTestament === 'Old'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak>
                @include('partials.book-progress-content', [
                    'testament' => 'Old',
                    'processedBooks' => $oldData['processed_books'],
                    'testamentProgress' => $oldData['testament_progress'],
                    'completedBooks' => $oldData['completed_books'],
                    'inProgressBooks' => $oldData['in_progress_books'],
                    'notStartedBooks' => $oldData['not_started_books']
                ])
            </div>

            <!-- New Testament Content -->
            <div x-show="activeTestament === 'New'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak>
                @include('partials.book-progress-content', [
                    'testament' => 'New',
                    'processedBooks' => $newData['processed_books'],
                    'testamentProgress' => $newData['testament_progress'],
                    'completedBooks' => $newData['completed_books'],
                    'inProgressBooks' => $newData['in_progress_books'],
                    'notStartedBooks' => $newData['not_started_books']
                ])
            </div>
        </div>
    </div>
</x-ui.card>

<script>
    /**
     * Book Progress Component - Manages Testament Selection with Persistence
     * Maintains user's testament preference across HTMX refreshes using localStorage
     */
    function bookProgressComponent(defaultTestament) {
        return {
            // State - Load from localStorage or use server default
            activeTestament: localStorage.getItem('selectedTestament') || defaultTestament,
            
            // Lifecycle - Set up persistence watcher
            init() {
                // Watch for testament changes and save to localStorage
                this.$watch('activeTestament', value => {
                    localStorage.setItem('selectedTestament', value);
                });
            }
        };
    }
</script> 