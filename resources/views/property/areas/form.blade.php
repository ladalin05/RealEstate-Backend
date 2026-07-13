<div class="cf-wrap">

    <style>
        /* Scoped to .cf-wrap so it never leaks into the rest of the admin panel */
        .cf-wrap {
            --cf-ink: #1f2430;
            --cf-muted: #6b7280;
            --cf-line: #e7e5e0;
            --cf-surface: #fafaf8;
            --cf-accent: #2f6f5e;
            --cf-accent-soft: #e6f0ed;
            --cf-gold: #c9973f;
            --cf-radius: 10px;
        }

        .cf-wrap { color: var(--cf-ink); font-size: 0.925rem; }

        .cf-section {
            padding: 1.1rem 1.15rem;
            margin-bottom: 1rem;
            background: var(--cf-surface);
            border: 1px solid var(--cf-line);
            border-radius: var(--cf-radius);
        }

        .cf-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--cf-accent);
            margin-bottom: 0.9rem;
        }

        .cf-eyebrow .cf-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--cf-accent);
            flex-shrink: 0;
        }

        .cf-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.9rem;
        }
        .cf-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.9rem;
        }
        @media (max-width: 576px) {
            .cf-grid-2, .cf-grid-3 { grid-template-columns: 1fr; }
        }

        .cf-field { margin-bottom: 0; }
        .cf-field + .cf-field { margin-top: 0.9rem; }
        .cf-grid-2 .cf-field + .cf-field,
        .cf-grid-3 .cf-field + .cf-field { margin-top: 0; }

        .cf-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--cf-ink);
            margin-bottom: 0.35rem;
        }
        .cf-label .req { color: #c0392b; margin-left: 2px; }
        .cf-label .hint {
            font-weight: 400;
            color: var(--cf-muted);
            font-size: 0.75rem;
            margin-left: 0.35rem;
        }

        .cf-input, .cf-select {
            width: 100%;
            padding: 0.55rem 0.75rem;
            font-size: 0.9rem;
            color: var(--cf-ink);
            background: #fff;
            border: 1px solid #d9d7d1;
            border-radius: 8px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .cf-input:focus, .cf-select:focus {
            outline: none;
            border-color: var(--cf-accent);
            box-shadow: 0 0 0 3px var(--cf-accent-soft);
        }
        .cf-input::placeholder { color: #b7b5af; }
        .cf-input.is-invalid, .cf-select.is-invalid { border-color: #c0392b; }

        .cf-slug-wrap { position: relative; }
        .cf-slug-wrap .cf-slug-prefix {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            color: var(--cf-muted);
            pointer-events: none;
        }
        .cf-slug-wrap .cf-input { padding-left: 1.4rem; }

        .cf-error {
            font-size: 0.76rem;
            color: #c0392b;
            margin-top: 0.3rem;
        }

        /* ── Status toggle ─────────────────────────────────────────── */
        .cf-status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .cf-status-text { font-size: 0.85rem; }
        .cf-status-text strong { display: block; font-size: 0.85rem; }
        .cf-status-text span { color: var(--cf-muted); font-size: 0.76rem; }

        .cf-switch {
            position: relative;
            width: 46px;
            height: 26px;
            flex-shrink: 0;
        }
        .cf-switch input { position: absolute; opacity: 0; width: 100%; height: 100%; margin: 0; cursor: pointer; }
        .cf-switch .track {
            position: absolute; inset: 0;
            background: #d9d7d1;
            border-radius: 999px;
            transition: background .15s ease;
        }
        .cf-switch .thumb {
            position: absolute;
            top: 3px; left: 3px;
            width: 20px; height: 20px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0,0,0,.25);
            transition: transform .15s ease;
        }
        .cf-switch input:checked ~ .track { background: var(--cf-gold); }
        .cf-switch input:checked ~ .thumb { transform: translateX(20px); }
        .cf-switch input:focus-visible ~ .track { box-shadow: 0 0 0 3px var(--cf-accent-soft); }

        /* ── Footer ────────────────────────────────────────────────── */
        .cf-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
            padding: 0.9rem 0 0.1rem;
            margin-top: 0.25rem;
            border-top: 1px solid var(--cf-line);
        }
        .cf-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform .1s ease, box-shadow .15s ease, background .15s ease;
        }
        .cf-btn:active { transform: translateY(1px); }
        .cf-btn-ghost {
            background: #fff;
            border-color: #d9d7d1;
            color: var(--cf-ink);
        }
        .cf-btn-ghost:hover { background: #f4f3f0; }
        .cf-btn-primary {
            background: var(--cf-accent);
            color: #fff;
        }
        .cf-btn-primary:hover { background: #285f50; box-shadow: 0 2px 8px rgba(47,111,94,.35); }
    </style>

    <form action="{{ $action }}" method="POST" id="area-form" class="ajax-form">
        @csrf

        {{-- Location --}}
        <div class="cf-section">
            <div class="cf-eyebrow"><span class="cf-dot"></span> Location</div>

            <div class="cf-grid-3">
                <div class="cf-field">
                    <label class="cf-label">Province<span class="req">*</span></label>
                    <select name="province_id" id="province" class="cf-select @error('province_id') is-invalid @enderror" required>
                        <option value="">-- Select Province --</option>
                        @foreach (getProvince() as $province)
                            <option value="{{ $province->id }}" {{ $form->province_id == $province->id ? 'selected' : '' }}> {{ $province->{'name_'.app()->getLocale()} }} </option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="cf-field">
                    <label class="cf-label">District<span class="req">*</span></label>
                    <select name="district_id" id="district" class="cf-select @error('district_id') is-invalid @enderror" required>
                        <option value="">-- Select District --</option>
                        @if(isset($form->district_id))
                            <option value="{{ $form->district_id }}" selected>{{ $form?->district?->name }}</option>
                        @endif
                    </select>
                    @error('district_id')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="cf-field">
                    <label class="cf-label">Commune</label>
                    <select name="commune_id" id="commune" class="cf-select @error('commune_id') is-invalid @enderror">
                        <option value="">-- Select Commune --</option>
                        @if(isset($form->commune_id))
                            <option value="{{ $form->commune_id }}" selected>{{ $form?->commune?->name }}</option>
                        @endif
                    </select>
                    @error('commune_id')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Basic info --}}
        <div class="cf-section">
            <div class="cf-eyebrow"><span class="cf-dot"></span> Basic information</div>

            <div class="cf-grid-2">
                <div class="cf-field">
                    <label for="name_en" class="cf-label">
                        Area Name (English)<span class="req">*</span>
                    </label>
                    <input type="text" name="name_en" id="name_en"
                           class="cf-input @error('name_en') is-invalid @enderror"
                           value="{{ old('name_en', $form->name_en ?? '') }}"
                           placeholder="e.g. Chamkarmon, Phnom Penh" required>
                    @error('name_en')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="cf-field">
                    <label for="name_km" class="cf-label">
                        Area Name (Khmer)
                    </label>
                    <input type="text" name="name_km" id="name_km"
                           class="cf-input @error('name_km') is-invalid @enderror"
                           value="{{ old('name_km', $form->name_km ?? '') }}"
                           placeholder="e.g. ចំការមន, ភ្នំពេញ">
                    @error('name_km')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="cf-grid-2" style="margin-top: 0.9rem;">
                <div class="cf-field">
                    <label for="slug" class="cf-label">
                        Slug<span class="req">*</span>
                        <span class="hint">auto-generated, editable</span>
                    </label>
                    <div class="cf-slug-wrap">
                        <span class="cf-slug-prefix">/</span>
                        <input type="text" name="slug" id="slug"
                               class="cf-input @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $form->slug ?? '') }}"
                               placeholder="chamkarmon-phnom-penh" required>
                    </div>
                    @error('slug')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="cf-field">
                    <label for="zip_code" class="cf-label">Zip / Postal Code</label>
                    <input type="text" name="zip_code" id="zip_code"
                           class="cf-input @error('zip_code') is-invalid @enderror"
                           value="{{ old('zip_code', $form->zip_code ?? '') }}"
                           placeholder="e.g. 120101" maxlength="10">
                    @error('zip_code')
                        <div class="cf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Image --}}
        <div class="cf-section">
            <div class="cf-eyebrow"><span class="cf-dot"></span> Area image</div>
            @php
                $hasImage = !empty($form->image ?? null);
                $imageUrl = $hasImage ? $form->image : '';
            @endphp

            <div class="d-flex justify-content-center w-100">
                <x-basic.uploader
                    input-name="image"
                    :url="old('image', $imageUrl)"
                    :path="old('image', $form->image ?? '')"
                    folder="areas"
                    width="200px"
                    height="150px"
                    caption="Recommended: 600×400px"
                />
            </div>
        </div>

        {{-- Status --}}
        <div class="cf-section">
            <div class="cf-eyebrow"><span class="cf-dot"></span> Status</div>

            @php $isActive = (old('status', $form->status ?? 1)) == 1; @endphp

            <div class="cf-status-row">
                <div class="cf-status-text">
                    <strong id="statusLabel">{{ $isActive ? 'Active' : 'Inactive' }}</strong>
                    <span>{{ $isActive ? 'Visible to visitors on the site' : 'Hidden from the site' }}</span>
                </div>

                <label class="cf-switch">
                    <input type="checkbox" id="status-toggle" {{ $isActive ? 'checked' : '' }}>
                    <span class="track"></span>
                    <span class="thumb"></span>
                </label>
                <input type="hidden" name="status" id="status" value="{{ $isActive ? 1 : 0 }}">
            </div>
        </div>

        {{-- Footer --}}
        <div class="cf-footer">
            <button type="button" class="cf-btn cf-btn-ghost" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="cf-btn cf-btn-primary" id="area-submit-btn">
                <i class="fa-solid fa-floppy-disk"></i>
                <span id="saveBtnText">{{ isset($form->id) ? 'Save Changes' : 'Create Area' }}</span>
            </button>
        </div>

    </form>
</div>

@include('property.areas.script')

<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────────
       Auto-name & auto-slug from selected locations
    ───────────────────────────────────────────── */
    let nameManuallyEdited = {{ !empty($form->name_en ?? null) ? 'true' : 'false' }};
    let slugManuallyEdited = {{ !empty($form->slug ?? null) ? 'true' : 'false' }};

    function buildNameFromSelects() {
        if (nameManuallyEdited) return;
        const communeText  = $('#commune').find(':selected').text();
        const provinceText = $('#province').find(':selected').text();
        const parts = [];
        if (communeText && $('#commune').val()) parts.push(communeText);
        if (provinceText && $('#province').val()) parts.push(provinceText);
        if (parts.length) {
            const built = parts.join(', ');
            $('#name_en').val(built);
            updateSlugFromName(built);
        }
    }

    function updateSlugFromName(value) {
        if (slugManuallyEdited) return;
        const slug = value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        $('#slug').val(slug);
    }

    /* location.script triggers native 'change' on #province/#district/#commune
       when populating cascaded options — these listeners just react to that */
    $('#province, #district, #commune').on('change', buildNameFromSelects);

    $('#name_en').on('input', function () {
        nameManuallyEdited = true;
        updateSlugFromName($(this).val());
    });

    $('#slug').on('input', function () {
        slugManuallyEdited = true;
    });

    /* ─────────────────────────────────────────────
       Status toggle
    ───────────────────────────────────────────── */
    $('#status-toggle').off('change.status').on('change.status', function () {
        const isActive = this.checked;
        $('#status').val(isActive ? 1 : 0);
        $('#statusLabel').text(isActive ? 'Active' : 'Inactive');
        $('#statusLabel').next('span').text(
            isActive ? 'Visible to visitors on the site' : 'Hidden from the site'
        );
    });

    /* ─────────────────────────────────────────────
       External ajax-form handler (if defined globally)
    ───────────────────────────────────────────── */
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#area-form');
    }

});
</script>