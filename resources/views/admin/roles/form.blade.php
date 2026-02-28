<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.card title="Test">
            <form action="{{ route('settings.users-management.roles.save', $form?->id) }}" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-basic.form.input :label="__('global.name_en')" name="name_en" value="{{ $form?->name_en }}" required />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.input :label="__('global.name_kh')" name="name_kh" value="{{ $form?->name_kh }}" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.textarea :label="__('global.description')" name="description" value="{{ $form?->description }}" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.checkbox :label="__('global.administrator')" id="administrator" name="administrator" value="1" checked="{{ $form?->administrator }}" />
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="permissions">
                    <!-- Trees -->
                    <div class="tree-checkbox-hierarchical">
                        <ul class="mb-0">
                            <li data-key="all" class="folder expanded">All
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
                <div class="text-end mt-3">
                    <a href="{{ route('settings.users-management.roles.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </form>
        </x-basic.card>
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
