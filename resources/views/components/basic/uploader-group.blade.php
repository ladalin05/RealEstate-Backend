@props([
    'folder' => '',
    'inputName' => 'gallery_images[]',
    'items' => [],
    'width' => '150px',
    'height' => '110px',
    'shape' => 'rounded',
    'label' => '',
    'addLabel' => 'Add Image',
    'max' => null,
])

@php
    $groupId = 'uploader-group-' . uniqid();
@endphp

<div class="uploader-group"
     id="{{ $groupId }}"
     data-folder="{{ $folder }}"
     @if($max) data-max="{{ $max }}" @endif>

    @if($label)
        <div class="uploader-group-header">
            <label class="form-label mb-0">{{ $label }}</label>
            <button type="button" class="uploader-group-add-btn uploader-add">
                <i class="fa fa-plus"></i> {{ $addLabel }}
            </button>
        </div>
    @endif

    <div class="uploader-items">
        @foreach ($items as $item)
            <x-basic.uploader
                :input-name="$inputName"
                :url="$item['url'] ?? ''"
                :path="$item['path'] ?? ''"
                :width="$width"
                :height="$height"
                :shape="$shape"
                :removable="true"
                :folder="$folder"
            />
        @endforeach
    </div>

    <div class="uploader-group-empty {{ count($items) ? 'd-none' : '' }}">
        <i class="fa fa-images"></i>
        <span>No images yet — click "{{ $addLabel }}" to get started.</span>
    </div>

    <template class="uploader-item-template">
        <x-basic.uploader
            :input-name="$inputName"
            :width="$width"
            :height="$height"
            :shape="$shape"
            :removable="true"
            :folder="$folder"
        />
    </template>
</div>

@once
<style>
    .uploader-group {
        width: 100%;
    }

    .uploader-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .uploader-group-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 7px;
        border: 1px solid #2f6f5e;
        background: #fff;
        color: #2f6f5e;
        cursor: pointer;
        transition: background .15s ease, color .15s ease;
    }
    .uploader-group-add-btn:hover {
        background: #2f6f5e;
        color: #fff;
    }
    .uploader-group-add-btn.disabled,
    .uploader-group-add-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .uploader-group .uploader-items {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        width: 100%;
    }

    .uploader-group-empty {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1.1rem 1rem;
        border: 1.5px dashed #d9d7d1;
        border-radius: 12px;
        background: #fafaf8;
        color: #6b7280;
        font-size: 0.82rem;
        margin-top: 0.25rem;
    }
    .uploader-group-empty i {
        font-size: 1.1rem;
        opacity: 0.6;
    }
    .uploader-group-empty.d-none {
        display: none;
    }
</style>

<script>
    (function ($) {

        // Toggle the empty-state placeholder whenever items are added/removed.
        function refreshEmptyState(group) {
            const $group = $(group);
            const count = $group.find('.uploader-items > .uploader-item').length;
            $group.find('.uploader-group-empty').toggleClass('d-none', count > 0);
        }

        // Enforce an optional max item count on the "Add" button.
        function refreshAddButtonState(group) {
            const $group = $(group);
            const max = parseInt($group.data('max'), 10);
            if (!max) return;

            const count = $group.find('.uploader-items > .uploader-item').length;
            $group.find('.uploader-group-add-btn')
                .prop('disabled', count >= max)
                .toggleClass('disabled', count >= max);
        }

        function refreshGroup(group) {
            refreshEmptyState(group);
            refreshAddButtonState(group);
        }

        // Run once for every group already on the page.
        $(function () {
            $('.uploader-group').each(function () {
                refreshGroup(this);
            });
        });

        document.addEventListener('click', function (e) {
            const addBtn = e.target.closest('.uploader-add');
            if (!addBtn) return;

            const group = addBtn.closest('.uploader-group');
            if (!group) return;

            const max = parseInt(group.dataset.max, 10);
            const count = group.querySelectorAll('.uploader-items > .uploader-item').length;
            if (max && count >= max) {
                e.stopImmediatePropagation();
                e.preventDefault();
                alert(`You can only add up to ${max} images.`);
                return;
            }

            // Defer so the DOM has the newly-cloned row before we re-check.
            setTimeout(() => refreshGroup(group), 0);
        }, true);

        // Re-check state after a row is removed (single clear or full delete).
        document.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.uploader-remove-row, .uploader-remove-image');
            if (!removeBtn) return;

            const group = removeBtn.closest('.uploader-group');
            if (!group) return;

            setTimeout(() => refreshGroup(group), 0);
        });
    })(jQuery);
</script>
@endonce