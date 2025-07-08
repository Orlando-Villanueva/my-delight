@props([
    'type' => 'text',
    'name' => '',
    'label' => null,
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'value' => ''
])

@php
    $inputClasses = 'form-input';
    if ($error) {
        $inputClasses .= ' border-destructive focus:ring-destructive';
    }
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        class="{{ $inputClasses }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    />
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div> 