<div class="text-center py-8" x-data="successMessageComponent()" x-init="init()" x-destroy="destroy()"
    @mouseenter="pauseTimer()" @mouseleave="resumeTimer()">
    <!-- Success Icon -->
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <!-- Success Message -->
    <h3 class="text-xl font-semibold text-gray-900 mb-2">Reading Logged Successfully! 🎉</h3>
    <p class="text-gray-600 mb-2">
        <strong>{{ $log->passage_text }}</strong> recorded for {{ $log->date_read->format('M d, Y') }}
    </p>

    @if ($log->notes_text)
        <p class="text-sm text-gray-500 italic mb-4">
            Notes: {{ Str::limit($log->notes_text, 60) }}
        </p>
    @endif

    @php
        // Check if this was a multi-chapter reading by looking for range in passage_text
        $isRange = strpos($log->passage_text, '-') !== false;
    @endphp

    @if ($isRange)
        <p class="text-sm text-gray-500 mb-4">
            💡 <em>Multiple chapters logged as separate entries for detailed progress tracking</em>
        </p>
    @endif

    <!-- Auto-close Countdown -->
    <p class="text-sm text-gray-500 mb-4">
        <span x-show="!isPaused">
            Modal will close in <span x-text="countdown" class="font-semibold text-primary-600 text-base"></span>
            second<span x-show="countdown !== 1">s</span>...
        </span>
        <span x-show="isPaused" x-cloak class="text-yellow-600 font-medium">
            ⏸️ Auto-close paused (hover away to resume)
        </span>
    </p>

    <!-- Action Buttons -->
    <div class="flex items-center justify-center space-x-4">
        <x-ui.button 
            variant="primary"
            size="default"
            @click="$dispatch('close-modal')"
        >
            Close
        </x-ui.button>

        <x-ui.button 
            variant="outline"
            size="default"
            hx-get="{{ route('logs.create') }}" 
            hx-target="#reading-log-modal-content"
            hx-swap="innerHTML" 
            hx-indicator="#modal-loading"
        >
            Log Another Reading
        </x-ui.button>
    </div>
</div>

{{-- Success Message Component with Auto-close Timer --}}

<script>
    /**
     * Alpine.js component for success message with auto-close functionality
     * Features: countdown timer, pause on hover, proper cleanup
     */
    function successMessageComponent() {
        return {
            // State
            countdown: 3,
            timerId: null,
            isPaused: false,

            // Initialization
            init() {
                this.$dispatch('readingLogAdded');
                this.startTimer();
            },

            // Cleanup
            destroy() {
                this.clearTimer();
            },

            // Timer Management
            startTimer() {
                if (this.timerId) return; // Prevent multiple timers

                this.timerId = setInterval(() => {
                    if (!this.isPaused) {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            this.clearTimer();
                            this.$dispatch('close-modal');
                        }
                    }
                }, 1000);
            },

            clearTimer() {
                if (this.timerId) {
                    clearInterval(this.timerId);
                    this.timerId = null;
                }
            },

            // User Interaction
            pauseTimer() {
                this.isPaused = true;
            },

            resumeTimer() {
                this.isPaused = false;
            }
        };
    }
</script>
