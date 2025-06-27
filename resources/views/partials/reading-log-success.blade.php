<div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
    <h3>Reading Logged Successfully! 🎉</h3>
    <p><strong>{{ $log->passage_text }}</strong> recorded for {{ $log->date_read->format('M d, Y') }}</p>
    @if($log->notes_text)
        <p><em>Notes: {{ $log->notes_text }}</em></p>
    @endif
    
    @php
        // Check if this was a multi-chapter reading by looking for range in passage_text
        $isRange = strpos($log->passage_text, '-') !== false;
    @endphp
    
    @if($isRange)
        <p class="text-sm text-gray-600 mt-2">
            💡 <em>Multiple chapters logged as separate entries for detailed progress tracking</em>
        </p>
    @endif
    
    <div style="margin-top: 10px;">
        <a href="{{ route('dashboard') }}">Return to Dashboard</a> | 
        <a href="{{ route('logs.create') }}">Log Another Reading</a>
    </div>
</div>

<script>
    // Clear the form after successful submission
    if (window.readingLogForm) {
        setTimeout(() => {
            // Reset form data
            const form = document.querySelector('[x-data]').__x.$data;
            if (form) {
                form.form.book_id = '';
                form.form.chapter = '';
                form.form.notes_text = '';
                form.availableChapters = 0;
                form.characterCount = 0;
            }
        }, 100);
    }
</script> 