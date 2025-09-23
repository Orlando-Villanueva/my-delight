@props([
    'selectedBook' => null,
    'selectedChapters' => null
])

<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 transition-all duration-200">
    <div class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
        <template x-if="selectedBook || selectedChapters">
            <div class="flex items-center gap-2 w-full">
                <!-- Book icon -->
                <div class="text-lg">📖</div>

                <!-- Selection text -->
                <div class="flex-1">
                    <span class="text-gray-600 dark:text-gray-400">Selection:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100 font-semibold"
                          x-text="selectedBook ? (selectedChapters ? `${selectedBook.name} ${selectedChapters}` : selectedBook.name) : ''"></span>
                </div>
            </div>
        </template>

        <template x-if="!selectedBook && !selectedChapters">
            <div class="flex items-center gap-2 w-full">
                <!-- Empty state -->
                <div class="text-lg">📚</div>
                <div class="flex-1">
                    <span class="text-gray-500 dark:text-gray-400 italic">No selection yet - choose a book to get started</span>
                </div>
            </div>
        </template>
    </div>
</div>