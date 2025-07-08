@props([
    'name' => '',
    'label' => null,
    'placeholder' => null,
    'value' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'id' => null,
    'options' => []
])

@php
    $selectId = $id ?? $name ?? 'select_' . uniqid();
    $hasError = !empty($error);
    
    $selectClasses = 'form-input';
    if ($hasError) {
        $selectClasses .= ' border-destructive focus:ring-destructive';
    }
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $selectId }}" class="form-label {{ $required ? 'after:content-[\'*\'] after:ml-0.5 after:text-destructive' : '' }}">
            {{ $label }}
        </label>
    @endif
    
    <select 
        id="{{ $selectId }}"
        name="{{ $name }}"
        class="{{ $selectClasses }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($hasError) aria-invalid="true" aria-describedby="{{ $selectId }}-error" @endif
        @if($help && !$hasError) aria-describedby="{{ $selectId }}-help" @endif
    >
        @if($placeholder)
            <option value="" {{ old($name, $value) === '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        
        @if(!empty($options))
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
    
    @if($hasError)
        <p id="{{ $selectId }}-error" class="form-error" role="alert">
            {{ $error }}
        </p>
    @elseif($help)
        <p id="{{ $selectId }}-help" class="text-sm text-muted-foreground mt-1">
            {{ $help }}
        </p>
    @endif
</div> 