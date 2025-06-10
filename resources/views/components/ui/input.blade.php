@props([
    'type' => 'text',
    'name' => '',
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'id' => null
])

@php
    $inputId = $id ?? $name ?? 'input_' . uniqid();
    $hasError = !empty($error);
    
    $inputClasses = 'block w-full px-3 py-2 border rounded-lg shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 text-neutral-600';
    if ($hasError) {
        $inputClasses .= ' border-error focus:ring-error focus:border-error';
    } else {
        $inputClasses .= ' border-neutral-300 focus:ring-primary focus:border-primary';
    }
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-neutral-700 mb-2 {{ $required ? 'after:content-[\'*\'] after:ml-0.5 after:text-error' : '' }}">
            {{ $label }}
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        id="{{ $inputId }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="{{ $inputClasses }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
        @if($help && !$hasError) aria-describedby="{{ $inputId }}-help" @endif
    />
    
    @if($hasError)
        <p id="{{ $inputId }}-error" class="text-sm text-error mt-1" role="alert">
            {{ $error }}
        </p>
    @elseif($help)
        <p id="{{ $inputId }}-help" class="text-sm text-neutral-500 mt-1">
            {{ $help }}
        </p>
    @endif
</div> 