@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => [],
    'required' => false,
    'size' => 5,
])

<div class="mb-3">
    @if($label ?? $slot->isNotEmpty())
        <label for="{{ $name }}" class="form-label">
            {{ $label ?? $slot }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}[]"
        id="{{ $name }}"
        multiple
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
    >
        @php
            $selected = old($name, $value) ?? [];
        @endphp

        @foreach($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                {{ in_array((string) $optionValue, array_map('strval', $selected)) ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>