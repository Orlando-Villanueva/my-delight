@props([
    'book' => null,
    'name' => 'chapter_input',
    'error' => null,
    'value' => '',
    'required' => true
])

@php
    $hasError = !empty($error);
    $componentId = 'chapter-selector-' . uniqid();
    $totalChapters = $book ? $book['chapters'] : 0;
@endphp

<div class="space-y-4"
     x-data="gridChapterSelector({{ $totalChapters }}, '{{ old($name, $value) }}')"
     x-init="init()"
     id="{{ $componentId }}"
     x-show="book">

    <!-- Book Title -->
    <div x-show="book" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
        <span x-text="book ? book.name : ''"></span>
        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2" x-text="book ? `(${book.chapters} chapters)` : ''"></span>
    </div>

    <!-- Instruction Text -->
    <div class="text-sm text-gray-600 dark:text-gray-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
        💡 <strong>Tip:</strong> Click a chapter, then click another to create a range (e.g., 3-7)
    </div>

    <!-- Chapter Grid -->
    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 gap-2"
         x-show="totalChapters > 0">
        <template x-for="chapter in chapterNumbers" :key="chapter">
            <button type="button"
                    @click="selectChapter(chapter)"
                    class="chapter-button aspect-square flex items-center justify-center text-sm font-medium rounded-lg border-2 transition-all duration-200"
                    :class="getChapterButtonClass(chapter)"
                    style="min-height: 44px; min-width: 44px;"
                    x-text="chapter">
            </button>
        </template>
    </div>

    <!-- No chapters message -->
    <div x-show="totalChapters === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
        <div class="text-lg mb-2">📖</div>
        <div>Select a book first to see chapters</div>
    </div>

    <!-- Back to Books Button -->
    <button type="button"
            @click="$dispatch('back-to-books')"
            class="btn btn-outline btn-sm inline-flex items-center gap-2"
            x-show="book">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Books
    </button>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden"
           name="{{ $name }}"
           x-model="chapterInput"
           {{ $required ? 'required' : '' }}>

    <!-- Error Message -->
    @if($hasError)
        <p class="form-error" role="alert">
            {{ $error }}
        </p>
    @endif
</div>

<script>
function gridChapterSelector(totalChapters, initialValue) {
    return {
        totalChapters: totalChapters,
        chapterNumbers: Array.from({length: totalChapters}, (_, i) => i + 1),
        selectedChapters: [],
        firstSelection: null,
        book: null,

        init() {
            // Parse initial value if provided
            if (initialValue) {
                this.parseInitialValue(initialValue);
            }
        },

        parseInitialValue(value) {
            if (!value) return;

            // Handle range format like "1-5"
            if (value.includes('-')) {
                const [start, end] = value.split('-').map(Number);
                if (start && end && start <= end) {
                    this.selectedChapters = Array.from({length: end - start + 1}, (_, i) => start + i);
                    this.firstSelection = start;
                }
            }
            // Handle single chapter like "3"
            else if (/^\d+$/.test(value)) {
                const chapter = parseInt(value);
                if (chapter >= 1 && chapter <= this.totalChapters) {
                    this.selectedChapters = [chapter];
                    this.firstSelection = chapter;
                }
            }
        },

        selectChapter(chapter) {
            if (!this.firstSelection) {
                // First click - select single chapter
                this.firstSelection = chapter;
                this.selectedChapters = [chapter];
            } else if (this.firstSelection === chapter) {
                // Same chapter clicked - unselect all
                this.resetSelection();
                return;
            } else {
                // Different chapter clicked - create range or reset
                const start = Math.min(this.firstSelection, chapter);
                const end = Math.max(this.firstSelection, chapter);

                // Check if chapters are sequential (no gaps)
                const isSequential = Math.abs(this.firstSelection - chapter) === (end - start);

                if (isSequential) {
                    // Create range selection
                    this.selectedChapters = Array.from({length: end - start + 1}, (_, i) => start + i);
                } else {
                    // Non-sequential - reset to new single selection
                    this.firstSelection = chapter;
                    this.selectedChapters = [chapter];
                }
            }

            this.dispatchSelectionEvent();
        },

        resetSelection() {
            this.firstSelection = null;
            this.selectedChapters = [];
        },

        get chapterInput() {
            if (this.selectedChapters.length === 0) return '';
            if (this.selectedChapters.length === 1) return this.selectedChapters[0].toString();

            const sorted = [...this.selectedChapters].sort((a, b) => a - b);
            const first = sorted[0];
            const last = sorted[sorted.length - 1];

            const isConsecutive = sorted.every((num, index) =>
                index === 0 || num === sorted[index - 1] + 1
            );

            return isConsecutive && sorted.length > 1 ? `${first}-${last}` : sorted.join(',');
        },

        dispatchSelectionEvent() {
            this.$dispatch('chapter-selected', {
                chapters: this.selectedChapters,
                input: this.chapterInput,
                book: this.book,
                displayText: this.getDisplayText()
            });
        },

        getDisplayText() {
            if (!this.book || this.selectedChapters.length === 0) {
                return '';
            }

            if (this.selectedChapters.length === 1) {
                return `${this.book.name} ${this.selectedChapters[0]}`;
            } else {
                const sorted = [...this.selectedChapters].sort((a, b) => a - b);
                const first = sorted[0];
                const last = sorted[sorted.length - 1];
                return `${this.book.name} ${first}-${last}`;
            }
        },

        getChapterButtonClass(chapter) {
            const isSelected = this.selectedChapters.includes(chapter);
            const isFirst = chapter === this.firstSelection;
            const isInRange = this.selectedChapters.length > 1 && isSelected;

            if (isSelected) {
                if (isFirst && this.selectedChapters.length === 1) {
                    return 'bg-primary-500 text-white border-primary-500 shadow-md chapter-button-selected';
                } else if (isInRange) {
                    return 'bg-primary-500 text-white border-primary-500 shadow-sm chapter-button-range';
                } else {
                    return 'bg-primary-500 text-white border-primary-500 shadow-md chapter-button-selected';
                }
            }

            return 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:border-primary-500/30 dark:hover:border-primary-500/50 hover:shadow-sm chapter-button-default';
        }
    }
}
</script>

<style>
.chapter-button {
    touch-action: manipulation;
}

.chapter-button:hover {
    transform: translateY(-1px);
}

.chapter-button:active {
    transform: translateY(0);
}

.chapter-button-selected {
    animation: selectPulse 0.3s ease-out;
}

.chapter-button-range {
    animation: rangePulse 0.2s ease-out;
}

@keyframes selectPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes rangePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Touch target compliance */
@media (max-width: 768px) {
    .chapter-button {
        min-height: 48px;
        min-width: 48px;
    }
}

/* Visual feedback for range selection */
.chapter-button-range {
    box-shadow: inset 0 0 0 1px rgba(51, 102, 204, 0.3);
}
</style>