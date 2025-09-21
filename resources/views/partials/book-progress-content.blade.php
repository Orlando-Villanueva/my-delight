{{-- Alpine.js compatible partial - uses currentData from parent Alpine component --}}

<!-- Progress Section -->
<div class="space-y-3 mb-6">
    <!-- Testament Label and Percentage -->
    <div class="flex items-center justify-between">
        <span class="text-base font-medium text-gray-700 dark:text-gray-300 leading-[1.5]" x-text="activeTestament + ' Testament'"></span>
        <span class="text-lg lg:text-xl font-bold text-primary-500 leading-[1.5]" x-text="currentData.testament_progress + '%'"></span>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3 overflow-hidden">
        <div class="bg-primary-500 h-3 transition-all duration-300"
             :style="`width: ${currentData.testament_progress}%`"></div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-3 gap-2 text-center text-sm">
        <div class="bg-success-500/10 dark:bg-success-500/20 rounded-lg py-2 px-1">
            <div class="font-bold text-success-500 text-base lg:text-lg leading-[1.5]" x-text="currentData.completed_books"></div>
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-tight">completed</div>
        </div>
        <div class="bg-primary-500/10 dark:bg-primary-500/20 rounded-lg py-2 px-1">
            <div class="font-bold text-primary-500 text-base lg:text-lg leading-[1.5]" x-text="currentData.in_progress_books"></div>
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-tight">in progress</div>
        </div>
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg py-2 px-1">
            <div class="font-bold text-gray-600 dark:text-gray-400 text-base lg:text-lg leading-[1.5]" x-text="currentData.not_started_books"></div>
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-tight">not started</div>
        </div>
    </div>
</div>

<!-- Books Grid -->
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
    <template x-for="book in currentData.processed_books" :key="book.name">
        <div class="relative p-4 rounded-lg border-2 text-center transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer group"
             :class="getBookStatusClasses(book.status)"
             :title="`${book.name}: ${book.chapters_read}/${book.chapter_count} chapters (${book.percentage}%)`">

            <!-- Book Name -->
            <div class="font-semibold text-sm mb-1 leading-[1.5]" x-text="book.name"></div>

            <!-- Progress Percentage -->
            <div class="text-sm opacity-90 mb-2 leading-[1.5]" x-text="book.percentage + '%'"></div>

            <!-- Mini Progress Bar for In-Progress Books -->
            <div x-show="book.status === 'in-progress'" class="w-full bg-white/30 rounded-full h-1 overflow-hidden">
                <div class="bg-white h-1 transition-all duration-500"
                     :style="`width: ${book.percentage}%`"></div>
            </div>

            <!-- Completion Badge -->
            <div x-show="book.status === 'completed'" class="absolute -top-1 -right-1 w-4 h-4 bg-success-500 rounded-full flex items-center justify-center">
                <div class="w-2 h-2 bg-white rounded-full"></div>
            </div>
        </div>
    </template>
</div>

<!-- Legend -->
<div class="flex items-center justify-center space-x-6 mt-6 pt-4 border-t border-gray-300 dark:border-gray-600">
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-success-500 rounded border-2 border-success-500"></div>
        <span class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Completed</span>
    </div>
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-primary-500 rounded border-2 border-primary-500"></div>
        <span class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">In Progress</span>
    </div>
    <div class="flex items-center space-x-2">
        <div class="w-3 h-3 bg-white dark:bg-gray-800 rounded border-2 border-gray-300 dark:border-gray-600"></div>
        <span class="text-sm text-gray-600 dark:text-gray-400 leading-[1.5]">Not Started</span>
    </div>
</div> 