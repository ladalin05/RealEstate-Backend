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
    </style>

    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <a href="{{ route('users-management.roles.index') }}" class="btn-back text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> {{ __('global.back') }}
                        </a>
                        <h2 class="mb-0 fw-bold h4">{{ $form?->id ? 'Edit Role' : 'Create New Role' }}</h2>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('users-management.roles.save', $form?->id) }}" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <x-basic.form.input :label="__('global.name_en')" name="name_en" value="{{ $form?->name_en }}" required class="form-control-lg" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.input :label="__('global.name_kh')" name="name_kh" value="{{ $form?->name_kh }}" class="form-control-lg" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-basic.form.textarea :label="__('global.description')" name="description" value="{{ $form?->description }}" rows="3" />
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="form-check form-switch custom-switch">
                                            <x-basic.form.checkbox :label="__('global.administrator')" id="administrator" name="administrator" value="1" checked="{{ $form?->administrator }}" />
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted opacity-25">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-secondary mb-3">
                                        <i class="bi bi-shield-lock me-2"></i>{{ __('global.permissions_assignment') }}
                                    </label>
                                    <input type="hidden" name="permissions">
                                    
                                    <div class="permission-tree-container p-3 border rounded bg-light">
                                        <div class="tree-checkbox-hierarchical">
                                            <ul class="tree-root">
                                                <li data-key="all" class="folder expanded">
                                                    <span class="tree-label fw-bold">Select All Permissions</span>
                                                    <ul>
                                                        @foreach ($menus as $menu)
                                                            <li data-key="{{ $menu->permissions->count() == 1 ? $menu->permissions->where('is_menu', 1)->first()->id : '' }}"
                                                                class="{{ $menu->children->count() || $menu->permissions->count() ? 'folder expanded' : '' }} {{ isset($access[$menu->id]) ? 'selected' : '' }}">
                                                                {{ $menu->name }}
                                                                <ul>
                                                                    @if (!empty($menu->children))
                                                                        @foreach ($menu->children as $child)
                                                                            <li data-key="{{ $child->permissions->count() == 1 ? $child->permissions->where('is_menu', 1)->first()->id : '' }}"
                                                                                class="{{ $child->children->count() || $child->permissions->count() ? 'folder expanded' : '' }} {{ isset($access[$child->id]) ? 'selected' : '' }}">
                                                                                {{ $child->name }}
                                                                                <ul>
                                                                                    @if ($child->children->count())
                                                                                        @foreach ($child->children as $grandchild)
                                                                                            <li data-key="{{ $grandchild->permissions->count() == 1 ? $grandchild->permissions->where('is_menu', 1)->first()->id : '' }}"
                                                                                                class="{{ $grandchild->permissions->count() ? 'folder expanded' : '' }} {{ isset($access[$grandchild->id]) ? 'selected' : '' }}">
                                                                                                {{ $grandchild->name }}
                                                                                                @if ($grandchild->permissions->count() > 1)
                                                                                                    <ul>
                                                                                                        @foreach ($grandchild->permissions as $permission)
                                                                                                            <li data-key="{{ $permission->id }}"
                                                                                                                class="{{ isset($access[$permission->slug]) ? 'selected' : '' }}">
                                                                                                                {{ $permission->name }}</li>
                                                                                                        @endforeach
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                    @if ($child->permissions->count() > 1)
                                                                                        @foreach ($child->permissions as $permission)
                                                                                            <li data-key="{{ $permission->id }}"
                                                                                                class="{{ isset($access[$permission->slug]) ? 'selected' : '' }}">
                                                                                                {{ $permission->name }}</li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </ul>
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                    @if ($menu->permissions->count() > 1)
                                                                        @foreach ($menu->permissions as $permission)
                                                                            <li data-key="{{ $permission->id }}" class="{{ isset($access[$permission->slug]) ? 'selected' : '' }}">
                                                                                {{ $permission->name }}
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
                                    <a href="{{ route('users-management.roles.index') }}" class="btn btn-light px-4 fw-semibold text-secondary">
                                        {{ __('global.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>{{ __('global.create_role') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
    @push('scripts')
        <script src="{{ asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/trees/fancytree_all.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/trees/fancytree_childcounter.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.tree-checkbox-hierarchical').fancytree({
                    checkbox: true,
                    selectMode: 3,
                    clickFolderMode: 4,
                });
                $('#administrator').on('change', function() {
                    $(document).find('#ft-id-1').hide();
                    if (!$(this).is(':checked')) {
                        $(document).find('#ft-id-1').show();
                    }
                });
                if ($('#administrator').is(':checked')) {
                    $(document).find('#ft-id-1').hide();
                }
            });

            // Form validation and submit
            var prossesing = false;
            $(document).on('submit', 'form', function(e) {
                e.preventDefault();
                if (prossesing) {
                    return;
                }
                prossesing = true;
                var selectedPermissions = [];
                $.ui.fancytree.getTree('.tree-checkbox-hierarchical').visit(function(node) {
                    if (node.isSelected()) {
                        selectedPermissions.push(node.key);
                    }
                });
                $('input[name="permissions"]').val(JSON.stringify(selectedPermissions));
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = new FormData(form[0]);
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        prossesing = false;
                        if (res.status == 'success') {
                            swalInit.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            setTimeout(function() {
                                window.location = res.redirect;
                            }, 1200);
                        } else {
                            swalInit.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: res.message || 'Something went wrong!',
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        prossesing = false;
                        var errors = xhr.responseJSON.errors;
                        var message = '';
                        $.each(errors, function(key, value) {
                            message += value + '<br>';
                        });
                        swalInit.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: message
                        });
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
