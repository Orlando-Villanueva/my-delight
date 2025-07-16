@props([
    'name' => 'chapter_input',
    'label' => 'Chapter(s)',
    'placeholder' => 'e.g., 3 or 1-5',
    'value' => '',
    'required' => true,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'id' => null,
    'maxlength' => 10
])

@php
    $inputId = $id ?? $name ?? 'chapter_selector_' . uniqid();
    $hasError = !empty($error);
    
    $inputClasses = 'form-input';
    if ($hasError) {
        $inputClasses .= ' border-destructive focus:ring-destructive';
    }
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if($label)
        <label for="{{ $inputId }}" class="form-label {{ $required ? 'after:content-[\'*\'] after:ml-0.5 after:text-destructive' : '' }}">
            📖 {{ $label }}
        </label>
    @endif
    
    <div class="space-y-3">
        {{-- Chapter Input Field --}}
        <input 
            type="text" 
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            maxlength="{{ $maxlength }}"
            class="{{ $inputClasses }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            @if($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            @if($help && !$hasError) aria-describedby="{{ $inputId }}-help" @endif
            {{ $attributes->except(['class']) }}
        >

        {{-- Dynamic Helper Text --}}
        <div class="text-sm text-gray-500">
            <div x-show="!form.book_id" x-cloak class="italic text-gray-400">
                📚 Please select a book first
            </div>
            <div x-show="form.book_id" x-cloak>
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Enter a single chapter (e.g., <strong>3</strong>) or range (e.g., <strong>1-5</strong>)</span>
                    </div>
                    <div class="flex items-center space-x-2 text-xs">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span><span x-text="form.book_name"></span> has <span x-text="availableChapters" class="font-semibold"></span> chapters available</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validation Feedback --}}
        <div x-show="form.chapter_validation_message" x-cloak
             :class="form.chapter_validation_valid ? 'text-success-600' : 'text-destructive'"
             class="text-sm font-medium flex items-center space-x-1">
            <svg x-show="form.chapter_validation_valid" class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="!form.chapter_validation_valid" class="w-4 h-4 text-destructive" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-text="form.chapter_validation_message"></span>
        </div>
    </div>
    
    @if($hasError)
        <p id="{{ $inputId }}-error" class="form-error" role="alert">
            {{ $error }}
        </p>
    @elseif($help)
        <p id="{{ $inputId }}-help" class="text-sm text-muted-foreground mt-1">
            {{ $help }}
        </p>
    @endif
</div> 