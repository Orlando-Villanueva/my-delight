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
    
    $selectClasses = 'block w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 text-neutral-600';
    if ($hasError) {
        $selectClasses .= ' border-error focus:ring-error focus:border-error';
    } else {
        $selectClasses .= ' border-neutral-300 focus:ring-primary focus:border-primary';
    }
@endphp

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-neutral-700 mb-2 {{ $required ? 'after:content-[\'*\'] after:ml-0.5 after:text-error' : '' }}">
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
        <p id="{{ $selectId }}-error" class="text-sm text-error mt-1" role="alert">
            {{ $error }}
        </p>
    @elseif($help)
        <p id="{{ $selectId }}-help" class="text-sm text-neutral-500 mt-1">
            {{ $help }}
        </p>
    @endif
</div> 