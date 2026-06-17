<style>
    .nav-hero .nav-item .nav-link.active {
        border: 1px solid #026EC7;
        background-color: transparent;
    }
</style>
    
<div class="py-1 px-3">
    <form
        action="{{ $action }}"
        method="POST"
        id="hero-form"
        class="ajax-form"
        enctype="multipart/form-data"
    >
        @csrf
        @if(isset($hero)) @method('PUT') @endif

        {{-- ── Language Tabs ── --}}
        <ul class="nav nav-pills nav-hero nav-sm mb-3 p-1 bg-light rounded-2" id="heroLangTab" role="tablist" style="width:fit-content; gap:2px;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-1 px-3 small" id="tab-en" data-bs-toggle="pill" data-bs-target="#pane-en" type="button" role="tab">
                    English
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-1 px-3 small" id="tab-kh" data-bs-toggle="pill" data-bs-target="#pane-kh" type="button" role="tab">
                    ខ្មែរ
                </button>
            </li>
        </ul>

        <div class="tab-content" id="heroLangTabContent">

            {{-- ══════════════════════════════
                 ENGLISH PANE
            ══════════════════════════════ --}}
            <div class="tab-pane fade show active" id="pane-en" role="tabpanel">

                {{-- Badge EN --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-uppercase small text-dark">
                        Badge Text
                        <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#dbeafe;color:#1d4ed8;">EN</span>
                    </label>
                    <input
                        type="text"
                        name="badge_en"
                        class="form-control form-control-sm @error('badge_en') is-invalid @enderror"
                        placeholder="e.g. New Release"
                        value="{{ old('badge_en', $hero->badge_en ?? '') }}"
                        maxlength="255"
                    >
                    @error('badge_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-2 text-muted opacity-25">
                <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Title</p>

                {{-- Title Main + Highlight EN --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-uppercase small text-dark">
                            Main Title
                            <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#dbeafe;color:#1d4ed8;">EN</span>
                        </label>
                        <input
                            type="text"
                            name="title_main_en"
                            class="form-control form-control-sm @error('title_main_en') is-invalid @enderror"
                            placeholder="e.g. Build Something"
                            value="{{ old('title_main_en', $hero->title_main_en ?? '') }}"
                            maxlength="255"
                        >
                        @error('title_main_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-uppercase small text-dark">
                            Highlight Title
                            <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#dbeafe;color:#1d4ed8;">EN</span>
                        </label>
                        <input
                            type="text"
                            name="title_highlight_en"
                            class="form-control form-control-sm @error('title_highlight_en') is-invalid @enderror"
                            placeholder="e.g. Extraordinary"
                            value="{{ old('title_highlight_en', $hero->title_highlight_en ?? '') }}"
                            maxlength="255"
                        >
                        @error('title_highlight_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-2 text-muted opacity-25">
                <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Subtitle</p>

                {{-- Subtitle EN --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-uppercase small text-dark">
                        Subtitle
                        <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#dbeafe;color:#1d4ed8;">EN</span>
                    </label>
                    <textarea
                        name="subtitle_en"
                        class="form-control form-control-sm @error('subtitle_en') is-invalid @enderror"
                        rows="3"
                        placeholder="Enter subtitle in English…"
                    >{{ old('subtitle_en', $hero->subtitle_en ?? '') }}</textarea>
                    @error('subtitle_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>{{-- /pane-en --}}

            {{-- ══════════════════════════════
                 KHMER PANE
            ══════════════════════════════ --}}
            <div class="tab-pane fade" id="pane-kh" role="tabpanel">

                {{-- Badge KH --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-uppercase small text-dark">
                        Badge Text
                        <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#fce7f3;color:#9d174d;">ខ្មែរ</span>
                    </label>
                    <input
                        type="text"
                        name="badge_kh"
                        class="form-control form-control-sm @error('badge_kh') is-invalid @enderror"
                        placeholder="e.g. ថ្មីៗ"
                        value="{{ old('badge_kh', $hero->badge_kh ?? '') }}"
                        maxlength="255"
                    >
                    @error('badge_kh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-2 text-muted opacity-25">
                <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Title</p>

                {{-- Title Main + Highlight KH --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-uppercase small text-dark">
                            Main Title
                            <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#fce7f3;color:#9d174d;">ខ្មែរ</span>
                        </label>
                        <input
                            type="text"
                            name="title_main_kh"
                            class="form-control form-control-sm @error('title_main_kh') is-invalid @enderror"
                            placeholder="e.g. បង្កើតអ្វីមួយ"
                            value="{{ old('title_main_kh', $hero->title_main_kh ?? '') }}"
                            maxlength="255"
                        >
                        @error('title_main_kh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-uppercase small text-dark">
                            Highlight Title
                            <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#fce7f3;color:#9d174d;">ខ្មែរ</span>
                        </label>
                        <input
                            type="text"
                            name="title_highlight_kh"
                            class="form-control form-control-sm @error('title_highlight_kh') is-invalid @enderror"
                            placeholder="e.g. អស្ចារ្យ"
                            value="{{ old('title_highlight_kh', $hero->title_highlight_kh ?? '') }}"
                            maxlength="255"
                        >
                        @error('title_highlight_kh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-2 text-muted opacity-25">
                <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Subtitle</p>

                {{-- Subtitle KH --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-uppercase small text-dark">
                        Subtitle
                        <span class="badge rounded-pill ms-1" style="font-size:.65rem;background:#fce7f3;color:#9d174d;">ខ្មែរ</span>
                    </label>
                    <textarea
                        name="subtitle_kh"
                        class="form-control form-control-sm @error('subtitle_kh') is-invalid @enderror"
                        rows="3"
                        placeholder="បញ្ចូល subtitle ជាភាសាខ្មែរ…"
                    >{{ old('subtitle_kh', $hero->subtitle_kh ?? '') }}</textarea>
                    @error('subtitle_kh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>{{-- /pane-kh --}}

        </div>{{-- /tab-content --}}

        {{-- ── Hero Image ── --}}
        <hr class="my-2 text-muted opacity-25">
        <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Media</p>

        <div class="mb-3">
            <label class="form-label fw-bold text-uppercase small text-dark">Hero Image</label>
            <div class="d-flex align-items-center gap-3">
                <div class="border rounded-2 bg-light d-flex align-items-center justify-content-center flex-shrink-0 overflow-hidden" style="width:72px;height:72px;">
                    @if(isset($hero) && $hero->image_url)
                        <img id="preview-img" src="{{ $hero->image_url }}" class="img-fluid object-fit-cover w-100 h-100">
                    @else
                        <img id="preview-img" src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" class="img-fluid object-fit-cover w-100 h-100">
                    @endif
                </div>
                <div class="flex-grow-1">
                    <input
                        type="file"
                        name="image"
                        id="image-input"
                        class="form-control form-control-sm @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/webp"
                    >
                    <div class="form-text small mt-1">JPG, PNG, WEBP · Max 2 MB · Recommended 1920×1080</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── Status ── --}}
        <hr class="my-2 text-muted opacity-25">
        <p class="text-uppercase fw-bold small text-muted mb-2" style="font-size:.7rem;letter-spacing:.06em;">Settings</p>

        <div class="mb-4">
            <label class="form-label fw-bold text-uppercase small text-dark">Status</label>
            <select name="status" id="status" class="form-select form-select-sm custom-select">
                <option value="1" {{ old('status', $hero->status ?? 0) == 1 ? 'selected' : '' }}>🟢 Active</option>
                <option value="0" {{ old('status', $hero->status ?? 0) == 0 ? 'selected' : '' }}>⚪ Inactive</option>
            </select>
        </div>

        {{-- ── Buttons ── --}}
        <div class="row g-2 justify-content-end">
            <div class="col-sm-3">
                <button type="button" class="btn btn-light w-100 py-2 text-muted fw-semibold small" data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm small">
                    <i class="bi bi-save2 me-1"></i>
                    {{ isset($hero) ? 'Save Changes' : 'Create Hero' }}
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    document.getElementById('image-input').addEventListener('change', function () {
        const [file] = this.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    });
</script>