@props([
    'name',
    'model' => null,
    'label' => null,
    'placeholderEn' => '',
    'placeholderKh' => '',
    'type' => 'input',
    'rows' => 3,
])

@php
    $fieldEn = "{$name}_en";
    $fieldKh = "{$name}_kh";

    $valueEn = old($fieldEn, stripslashes($model?->{$fieldEn} ?? ''));
    $valueKh = old($fieldKh, stripslashes($model?->{$fieldKh} ?? ''));
@endphp

@once
    <style>
        .lang-input-group {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1.5px solid #dee2e6;
            border-radius: 0.6rem;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .lang-input-group:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .lang-input-group.align-items-start {
            align-items: flex-start;
        }

        .lang-input-group .lang-pane {
            flex-grow: 1;
            min-width: 0;
        }

        .lang-input-group .form-control {
            box-shadow: none !important;
            background: transparent;
            resize: vertical;
            border: 0 !important;
        }

        .lang-input-group .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        .khmer-font {
            font-family: "Khmer OS Battambang", "Noto Sans Khmer", sans-serif;
        }

        .lang-tab-group {
            display: flex;
            padding: 2px;
            margin: 6px;
            background: #f1f3f5;
            border-radius: 0.5rem;
            flex-shrink: 0;
        }

        .lang-input-group.align-items-start .lang-tab-group {
            align-self: flex-start;
        }

        .lang-tab-btn {
            border: none;
            background: transparent;
            color: #6c757d;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 0.4rem !important;
            transition: all 0.2s ease;
            line-height: 1.5;
        }

        .lang-tab-btn.active {
            background: #0d6efd;
            color: #fff;
            box-shadow: 0 1px 3px rgba(13, 110, 253, 0.35);
        }

        .lang-tab-btn:hover:not(.active) {
            background: #e2e6ea;
            color: #495057;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.lang-tab-group').forEach(group => {
                const langKey = group.dataset.langGroup;

                group.querySelectorAll('.lang-tab-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const lang = btn.dataset.lang;

                        group.querySelectorAll('.lang-tab-btn').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');

                        document.querySelectorAll(`.lang-pane[data-lang-group="${langKey}"]`).forEach(pane => {
                            pane.classList.toggle('d-none', pane.dataset.lang !== lang);
                            pane.classList.toggle('active', pane.dataset.lang === lang);
                        });
                    });
                });
            });
        });
    </script>
@endonce

<div class="mb-3">
    @if ($label)
        <label class="form-label fw-semibold">{{ $label }}</label>
    @endif

    <div class="lang-input-group {{ $type === 'textarea' ? 'align-items-start' : '' }}" data-lang-group="{{ $name }}">
        <div class="lang-pane active" data-lang-group="{{ $name }}" data-lang="en">
            @if ($type === 'textarea')
                <textarea name="{{ $fieldEn }}" class="form-control" rows="{{ $rows }}"
                    placeholder="{{ $placeholderEn }}">{{ $valueEn }}</textarea>
            @else
                <input type="text" name="{{ $fieldEn }}" class="form-control"
                    value="{{ $valueEn }}" placeholder="{{ $placeholderEn }}">
            @endif
        </div>

        <div class="lang-pane d-none" data-lang-group="{{ $name }}" data-lang="kh">
            @if ($type === 'textarea')
                <textarea name="{{ $fieldKh }}" class="form-control khmer-font" rows="{{ $rows }}"
                    placeholder="{{ $placeholderKh }}">{{ $valueKh }}</textarea>
            @else
                <input type="text" name="{{ $fieldKh }}" class="form-control khmer-font"
                    value="{{ $valueKh }}" placeholder="{{ $placeholderKh }}">
            @endif
        </div>

        <div class="btn-group lang-tab-group" data-lang-group="{{ $name }}" role="group">
            <button type="button" class="btn lang-tab-btn active" data-lang="en">EN</button>
            <button type="button" class="btn lang-tab-btn" data-lang="kh">KH</button>
        </div>
    </div>

    @error($fieldEn)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    @error($fieldKh)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>