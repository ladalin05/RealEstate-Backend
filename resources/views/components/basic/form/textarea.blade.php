@props([
    'disabled' => false,
    'label' => null,
    'value' => null,
])
<div class="mb-3">
    <label class="form-label">{{ $label }}</label>
    <textarea @disabled($disabled) {{ $attributes->merge(['class' => 'form-control']) }}>{{ $value }}</textarea>
</div>
