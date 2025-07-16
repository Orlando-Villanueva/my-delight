@props([
    'name' => '',
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'id' => null,
    'rows' => 4,
    'maxlength' => null,
    'showCounter' => false
])

@php
    $textareaId = $id ?? $name ?? 'textarea_' . uniqid();
    $hasError = !empty($error);
    
    $textareaClasses = 'form-input resize-y';
    if ($hasError) {
        $textareaClasses .= ' border-destructive focus:ring-destructive';
    }
    
    $currentValue = old($name, $value);
    $currentLength = strlen($currentValue);
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $textareaId }}" class="form-label {{ $required ? 'after:content-[\'*\'] after:ml-0.5 after:text-destructive' : '' }}">
            {{ $label }}
        </label>
    @endif
    
    <textarea 
        id="{{ $textareaId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        class="{{ $textareaClasses }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
        @if($hasError) aria-invalid="true" aria-describedby="{{ $textareaId }}-error" @endif
        @if($help && !$hasError) aria-describedby="{{ $textareaId }}-help" @endif
        @if($showCounter && $maxlength) x-data="{ count: {{ $currentLength }} }" x-on:input="count = $event.target.value.length" @endif
    >{{ $currentValue }}</textarea>
    
    @if($showCounter && $maxlength)
        <div class="flex justify-between items-center">
            <div class="flex-1">
                @if($hasError)
                    <p id="{{ $textareaId }}-error" class="form-error" role="alert">
                        {{ $error }}
                    </p>
                @elseif($help)
                    <p id="{{ $textareaId }}-help" class="text-sm text-muted-foreground mt-1">
                        {{ $help }}
                    </p>
                @endif
            </div>
            <div class="text-sm text-muted-foreground" x-text="`${count}/${!! $maxlength !!}`">
                {{ $currentLength }}/{{ $maxlength }}
            </div>
        </div>
    @else
        @if($hasError)
            <p id="{{ $textareaId }}-error" class="form-error" role="alert">
                {{ $error }}
            </p>
        @elseif($help)
            <p id="{{ $textareaId }}-help" class="text-sm text-muted-foreground mt-1">
                {{ $help }}
            </p>
        @endif
    @endif
</div> 