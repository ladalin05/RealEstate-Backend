@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option',
    'required' => false,
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
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
    >
        @if($placeholder)
            <option value="" {{ old($name, $value) ? '' : 'selected' }} disabled>
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                {{ (string) old($name, $value) === (string) $optionValue ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>