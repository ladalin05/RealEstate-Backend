<x-app-layout>
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #f8f9fc;
            --border-color: #e3e6f0;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .card-header i { margin-right: 10px; }

        .form-label {
            font-weight: 600;
            color: #4e5154;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .select2-container .select2-selection--single {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            height: auto;
        }

        .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: auto !important;
        }

        .select2-selection__arrow b {
            top: 50% !important;
        }

        .form-control {
            padding: 0.8rem 1rem !important;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group-text {
            background-color: #f1f3f9;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            transform: translateY(-1px);
        }

        .image-upload-wrapper {
            background: #fdfdfd;
            border: 2px dashed var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            position: relative;
        }

        .image-upload-wrapper:hover {
            border-color: var(--primary-color);
            background: #f8faff;
        }

        .image-upload-wrapper.uploading {
            border-color: #f6c23e;
            background: #fffbf0;
        }

        .image-upload-wrapper.upload-done {
            border-color: var(--success-color);
            background: #f0fdf8;
        }

        .img-preview-custom {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .img-preview-custom.hidden { display: none; }

        .upload-spinner {
            display: none;
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.75);
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .upload-spinner.active { display: flex; }

        .spinner-border-sm { width: 1.2rem; height: 1.2rem; }

        .btn-remove-image {
            display: none;
            margin-top: 8px;
        }

        .section-divider {
            height: 1px;
            background: var(--border-color);
            margin: 2rem 0;
        }

        .gallery-image-item {
            background: #fff;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .gallery-image-item.uploading { border-color: #f6c23e; }
        .gallery-image-item.upload-done { border-color: var(--success-color); }
        .gallery-image-item.upload-error { border-color: #e74a3b; }

        .upload-status-badge {
            font-size: 0.72rem;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* Section block (repeatable blog_post_sections row) */
        .section-block {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            position: relative;
        }

        .section-block .section-number {
            position: absolute;
            top: -12px;
            left: 16px;
            background: var(--primary-color);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 12px;
            border-radius: 20px;
        }

        .list-item-row { margin-bottom: 8px; }

        .tag-pill {
            display: inline-flex;
            align-items: center;
            background: #eef1fb;
            color: var(--primary-color);
            border-radius: 20px;
            padding: 5px 8px 5px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0 6px 6px 0;
        }

        .tag-pill button {
            background: none;
            border: none;
            color: var(--primary-color);
            margin-left: 6px;
            line-height: 1;
            font-size: 1rem;
        }
    </style>

    @php
        $isEdit      = isset($isEdit) && $isEdit;
        $post        = $post ?? null;

        $formAction  = $isEdit
            ? route('blogs.posts.edit', ['id' => $post->id])
            : route('blogs.posts.add');

        $pageTitle   = $isEdit ? 'Edit Blog Post' : 'Add New Blog Post';
        $submitLabel = $isEdit ? 'Save Changes'   : 'Create Post';

        // Helper: old() → model value → default
        $val = fn(string $field, mixed $default = '') => old($field, $post?->{$field} ?? $default);

        $selectedTagIds = $isEdit ? $post->tags->pluck('id')->toArray() : old('tag_ids', []);

        // Existing MinIO paths
        $featuredImagePath = $isEdit ? ($post->featured_image ?? '') : '';

        $minioBase        = rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/';
        $featuredImageUrl = $featuredImagePath ? $minioBase . $featuredImagePath : null;

        $statusOptions = ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'];

        // Existing sections (edit mode) serialized for the JS to bootstrap with
        $existingSections = $isEdit
            ? $post->sections->map(function ($s) use ($minioBase) {
                return [
                    'heading'     => $s->heading,
                    'content'     => $s->content,
                    'list_items'  => $s->list_items ?? [],
                    'images'      => $s->images->map(fn ($img) => [
                        'path' => $img->image_path,
                        'url'  => $minioBase . $img->image_path,
                    ])->values(),
                ];
            })->values()
            : collect();
    @endphp

    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('blogs.posts.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h3 class="h4 mb-0 text-gray-800">{{ $pageTitle }}</h3>
                    </div>

                    <form action="{{ $formAction }}"
                          method="POST"
                          id="blogPostForm"
                          class="ajax-form">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        {{-- ═══════════════════════════════════════════
                             CARD 1 – Basic Information
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-info-circle"></i> Basic Information
                            </div>
                            <div class="card-body">

                                {{-- Title --}}
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Title *</label>
                                        <input type="text"
                                               name="title"
                                               value="{{ $val('title') }}"
                                               class="form-control"
                                               placeholder="e.g. 10 Tips for First-Time Homebuyers"
                                               required
                                               maxlength="255">
                                    </div>
                                </div>

                                {{-- Category / Status / Published At --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Category</label>
                                        <select class="form-control select2" name="category_id">
                                            <option value="">— None —</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ (string) $val('category_id') === (string) $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Status *</label>
                                        <select class="form-control" name="status" id="status" required>
                                            @foreach ($statusOptions as $sVal => $sLabel)
                                                <option value="{{ $sVal }}"
                                                    {{ $val('status', 'draft') === $sVal ? 'selected' : '' }}>
                                                    {{ $sLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4" id="published-at-row">
                                        <label class="form-label">Publish Date</label>
                                        <input type="datetime-local"
                                               name="published_at"
                                               value="{{ $val('published_at') }}"
                                               class="form-control">
                                    </div>
                                </div>

                                {{-- Excerpt --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">Excerpt
                                            <small class="text-muted fw-normal">(shown on listing page)</small>
                                        </label>
                                        <textarea name="excerpt" class="form-control" rows="2"
                                                  maxlength="500"
                                                  placeholder="Short summary…">{{ $val('excerpt') }}</textarea>
                                    </div>
                                </div>

                                {{-- Overview --}}
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Overview
                                            <small class="text-muted fw-normal">(intro paragraph at top of post)</small>
                                        </label>
                                        <textarea id="elm1" name="overview" class="form-control tinymce">{{ $val('overview') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 2 – Featured Image
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-image"></i> Featured Image
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="image-upload-wrapper {{ $featuredImageUrl ? 'upload-done' : '' }}" id="featured-image-wrapper">
                                            <div class="upload-spinner" id="featured-image-spinner">
                                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                                Uploading…
                                            </div>
                                            <input type="file" id="featured_image_file" class="d-none" accept="image/*">
                                            <input type="hidden" name="featured_image" id="featured_image_path"
                                                   value="{{ $featuredImagePath }}">
                                            <button type="button" class="btn btn-dark mb-2" id="featured-image-btn">
                                                {{ $isEdit && $featuredImagePath ? 'Change Image' : 'Select Image' }}
                                            </button>
                                            <p class="small text-muted mb-0">Recommended: 1200×630px</p>
                                            <img id="featured-image-preview"
                                                 src="{{ $featuredImageUrl ?? '#' }}"
                                                 class="img-preview-custom {{ $featuredImageUrl ? '' : 'hidden' }}">
                                            <div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-remove-image mt-2"
                                                        id="featured-image-remove"
                                                        style="{{ $featuredImagePath ? 'display:inline-block' : '' }}">
                                                    <i class="fa fa-times me-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 3 – Tags
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-tags"></i> Tags
                            </div>
                            <div class="card-body">
                                <select name="tag_ids[]" class="form-control select2-multiple" multiple="multiple" data-tags="true">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            {{ in_array($tag->id, $selectedTagIds) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="small text-muted mt-2 mb-0">Pick existing tags or type a new one and press enter to create it.</p>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 4 – Content Sections (repeatable)
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-layer-group"></i> Content Sections</span>
                                <button type="button" id="add-section" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Section
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="sections-wrapper"></div>
                                <p class="text-muted small mb-0" id="no-sections-msg">No sections added yet. Click "Add Section" to build out the body of this post.</p>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════
                             CARD 5 – SEO
                        ════════════════════════════════════════════ --}}
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-search"></i> SEO
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Meta Title
                                            <small class="text-muted fw-normal">(falls back to title)</small>
                                        </label>
                                        <input type="text" name="meta_title"
                                               value="{{ $val('meta_title') }}"
                                               class="form-control" maxlength="255">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Meta Description
                                            <small class="text-muted fw-normal">(falls back to excerpt)</small>
                                        </label>
                                        <input type="text" name="meta_description"
                                               value="{{ $val('meta_description') }}"
                                               class="form-control" maxlength="500">
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow" id="submit-btn">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> {{ $submitLabel }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden template used to clone new section blocks --}}
    <template id="section-template">
        <div class="section-block" data-index="__INDEX__">
            <span class="section-number">Section __NUM__</span>

            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-sm btn-outline-danger remove-section">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label">Heading</label>
                <input type="text" class="form-control section-heading" maxlength="500">
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea class="form-control section-content" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Bullet Points</label>
                <div class="list-items-wrapper"></div>
                <button type="button" class="btn btn-outline-secondary btn-sm add-list-item">
                    <i class="fa fa-plus"></i> Add Bullet Point
                </button>
            </div>

            <div>
                <label class="form-label">Section Images</label>
                <button type="button" class="btn btn-dark btn-sm add-section-image">
                    <i class="fa fa-upload"></i> Add Image
                </button>
                <input type="file" class="d-none section-image-file" accept="image/*">
                <div class="section-images-wrapper d-flex flex-wrap gap-2 mt-3"></div>
            </div>
        </div>
    </template>

    <template id="list-item-template">
        <div class="input-group list-item-row">
            <input type="text" class="form-control list-item-input" maxlength="1000" placeholder="Bullet point text">
            <button type="button" class="btn btn-outline-danger remove-list-item"><i class="fa fa-times"></i></button>
        </div>
    </template>

    <template id="section-image-template">
        <div class="position-relative gallery-thumb" style="width:90px;">
            <img class="img-preview-custom" style="margin-top:0;width:90px;height:70px;object-fit:cover;" src="#">
            <button type="button" class="btn btn-sm btn-outline-danger remove-section-image"
                    style="position:absolute;top:-8px;right:-8px;border-radius:50%;padding:0 6px;line-height:1.6;">
                &times;
            </button>
            <span class="badge bg-warning text-dark upload-status-badge uploading-badge" style="position:absolute;bottom:2px;left:2px;display:none;">…</span>
        </div>
    </template>

    @push('scripts')
        <script>
        $(document).ready(function () {

            // ── Select2 ────────────────────────────────────────────────────
            $('.select2').select2({ width: '100%' });
            $('.select2-multiple').select2({
                placeholder: 'Select or type to add tags',
                allowClear: true,
                width: '100%',
                tags: true
            });

            // ── Show/hide publish date based on status ──────────────────────
            function togglePublishedAt() {
                $('#published-at-row').toggle($('#status').val() === 'published');
            }
            $('#status').on('change', togglePublishedAt);
            togglePublishedAt();

            // ── CSRF token ─────────────────────────────────────────────────
            const CSRF = $('meta[name="csrf-token"]').attr('content');

            // ── uploadToMinio / deleteFromMinio ──────────────────────────────
            function uploadToMinio(file, folder = 'blog') {
                const fd = new FormData();
                fd.append('file', file);
                fd.append('folder', folder);
                return $.ajax({
                    url: '{{ route("uploads.store") }}',
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': CSRF }
                }).then(res => res.data);
            }

            function deleteFromMinio(path) {
                if (!path) return;
                $.ajax({
                    url: '{{ route("uploads.destroy") }}',
                    method: 'DELETE',
                    data: JSON.stringify({ path }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': CSRF }
                });
            }

            // ── Single-image uploader (Featured Image) ───────────────────────
            function initSingleUploader(cfg) {
                const $file    = $(cfg.fileInputId);
                const $path    = $(cfg.pathInputId);
                const $preview = $(cfg.previewId);
                const $wrapper = $(cfg.wrapperId);
                const $spinner = $(cfg.spinnerId);
                const $remove  = $(cfg.removeId);
                const $btn     = $(cfg.triggerBtnId);

                $btn.on('click', () => $file.trigger('click'));

                $file.on('change', async function () {
                    const file = this.files[0];
                    if (!file) return;

                    $spinner.addClass('active');
                    $wrapper.addClass('uploading').removeClass('upload-done');
                    $btn.prop('disabled', true);

                    try {
                        const prevPath = $path.val();
                        if (prevPath) deleteFromMinio(prevPath);

                        const result = await uploadToMinio(file, cfg.folder);

                        $path.val(result.path);
                        $preview.attr('src', result.public_url).removeClass('hidden');
                        $remove.show();
                        $wrapper.removeClass('uploading').addClass('upload-done');
                        $btn.text('Change Image');
                    } catch (err) {
                        console.error('Upload failed', err);
                        alert('Upload failed. Please try again.');
                        $wrapper.removeClass('uploading');
                    } finally {
                        $spinner.removeClass('active');
                        $btn.prop('disabled', false);
                        $file.val('');
                    }
                });

                $remove.on('click', function () {
                    const path = $path.val();
                    if (path && confirm('Remove this image?')) {
                        deleteFromMinio(path);
                        $path.val('');
                        $preview.attr('src', '#').addClass('hidden');
                        $remove.hide();
                        $wrapper.removeClass('upload-done uploading');
                        $btn.text('Select Image');
                    }
                });
            }

            initSingleUploader({
                fileInputId:  '#featured_image_file',
                pathInputId:  '#featured_image_path',
                previewId:    '#featured-image-preview',
                wrapperId:    '#featured-image-wrapper',
                spinnerId:    '#featured-image-spinner',
                removeId:     '#featured-image-remove',
                triggerBtnId: '#featured-image-btn',
                folder:       'blog/featured',
            });

            // ── Dynamic sections ──────────────────────────────────────────
            let sectionIndex = 0;
            const $sectionsWrapper = $('#sections-wrapper');
            const $noSectionsMsg   = $('#no-sections-msg');

            function refreshSectionNumbers() {
                $sectionsWrapper.find('.section-block').each(function (i) {
                    $(this).find('.section-number').text('Section ' + (i + 1));
                });
                $noSectionsMsg.toggle($sectionsWrapper.find('.section-block').length === 0);
            }

            function buildSectionFieldNames($block) {
                const idx = $block.data('index');
                $block.find('.section-heading').attr('name', `sections[${idx}][heading]`);
                $block.find('.section-content').attr('name', `sections[${idx}][content]`);
                $block.find('.list-item-input').each(function (i) {
                    $(this).attr('name', `sections[${idx}][list_items][${i}]`);
                });
                $block.find('.section-image-path').each(function (i) {
                    $(this).attr('name', `sections[${idx}][images][${i}]`);
                });
            }

            function addSection(data = null) {
                const tpl = document.getElementById('section-template').innerHTML;
                const idx = sectionIndex++;
                const html = tpl.replaceAll('__INDEX__', idx).replaceAll('__NUM__', idx + 1);
                const $block = $(html);
                $sectionsWrapper.append($block);

                if (data) {
                    $block.find('.section-heading').val(data.heading || '');
                    $block.find('.section-content').val(data.content || '');
                    (data.list_items || []).forEach(text => addListItem($block, text));
                    (data.images || []).forEach(img => addSectionImage($block, img.path, img.url, true));
                }

                buildSectionFieldNames($block);
                refreshSectionNumbers();
                return $block;
            }

            function addListItem($block, value = '') {
                const tpl = document.getElementById('list-item-template').innerHTML;
                const $row = $(tpl);
                $row.find('.list-item-input').val(value);
                $block.find('.list-items-wrapper').append($row);
                buildSectionFieldNames($block);
            }

            function addSectionImage($block, path, url, alreadyUploaded = false) {
                const tpl = document.getElementById('section-image-template').innerHTML;
                const $thumb = $(tpl);
                $thumb.append(`<input type="hidden" class="section-image-path" value="${path || ''}">`);
                if (url) $thumb.find('img').attr('src', url);
                if (!alreadyUploaded) $thumb.find('.uploading-badge').show();
                $block.find('.section-images-wrapper').append($thumb);
                buildSectionFieldNames($block);
                return $thumb;
            }

            $('#add-section').on('click', () => addSection());

            $(document).on('click', '.remove-section', function () {
                const $block = $(this).closest('.section-block');
                $block.find('.section-image-path').each(function () {
                    const p = $(this).val();
                    if (p) deleteFromMinio(p);
                });
                $block.remove();
                refreshSectionNumbers();
            });

            $(document).on('click', '.add-list-item', function () {
                addListItem($(this).closest('.section-block'), '');
            });

            $(document).on('click', '.remove-list-item', function () {
                const $block = $(this).closest('.section-block');
                $(this).closest('.list-item-row').remove();
                buildSectionFieldNames($block);
            });

            $(document).on('click', '.add-section-image', function () {
                $(this).closest('.section-block').find('.section-image-file').trigger('click');
            });

            $(document).on('change', '.section-image-file', async function () {
                const file = this.files[0];
                if (!file) return;
                const $block = $(this).closest('.section-block');
                const $thumb = addSectionImage($block, '', URL.createObjectURL(file), false);

                try {
                    const result = await uploadToMinio(file, 'blog/sections');
                    $thumb.find('.section-image-path').val(result.path);
                    $thumb.find('img').attr('src', result.public_url);
                    $thumb.find('.uploading-badge').hide();
                    buildSectionFieldNames($block);
                } catch (err) {
                    console.error('Section image upload failed', err);
                    alert('Image upload failed. Please try again.');
                    $thumb.remove();
                } finally {
                    $(this).val('');
                }
            });

            $(document).on('click', '.remove-section-image', function () {
                const $thumb = $(this).closest('.gallery-thumb');
                const path = $thumb.find('.section-image-path').val();
                if (path) deleteFromMinio(path);
                const $block = $thumb.closest('.section-block');
                $thumb.remove();
                buildSectionFieldNames($block);
            });

            // ── Bootstrap existing sections in edit mode ─────────────────────
            const existingSections = @json($existingSections);
            existingSections.forEach(s => addSection(s));
            refreshSectionNumbers();

            // ── Submit guard ───────────────────────────────────────────────
            $('#blogPostForm').on('submit', function (e) {
                if ($('#featured-image-wrapper.uploading').length > 0 ||
                    $('.uploading-badge:visible').length > 0) {
                    e.preventDefault();
                    alert('Please wait — some images are still uploading.');
                    return false;
                }
            });

        }); // end ready
        </script>
    @endpush
</x-app-layout>