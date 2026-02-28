<x-app-layout>
    <x-basic.breadcrumb>
    </x-basic.breadcrumb>
    <!-- Content area -->
    <div class="content">
        <x-basic.card :title="$title">
            <x-basic.form action="{{ route('settings.users-management.users.save', $form?->id) }}" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.first_name_en') }}" name="first_name_en" value="{{ $form?->first_name_en }}" :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.last_name_en') }}" name="last_name_en" value="{{ $form?->last_name_en }}" :required="true" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.first_name_kh') }}" name="first_name_kh" value="{{ $form?->first_name_kh }}" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.last_name_kh') }}" name="last_name_kh" value="{{ $form?->last_name_kh }}" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.phone_number') }}" name="phone" value="{{ $form?->phone }}" />
                    </div>
                    <div class="col-md-6">
                        <x-basic.form.text label="{{ __('global.email') }}" name="email" value="{{ $form?->email }}" :required="true" />
                    </div>
                    {{-- @if (empty($form->id)) --}}
                        <div class="col-md-6">
                            <x-basic.form.text label="{{ __('global.password') }}" name="password" type="password" :required="$form?->id ? false : true" />
                        </div>
                        <div class="col-md-6">
                            <x-basic.form.multiple-select label="{{ __('global.role') }}" name="role_id[]" :options="$roles" :selected="$form?->roles?->pluck('id')->toArray()" :required="true" />
                        </div>
                    {{-- @endif --}}
                    <div class="col-md-6">
                        <x-extensions.image-cropper title="{{ __('global.avatar') }}" label="{{ __('global.avatar') }}" name="avatar" :image="$form?->avatar" />
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('settings.users-management.users.index') }}" class="btn btn-warning">{{ __('global.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </x-basic.form>
        </x-basic.card>
    </div>
    <!-- /content area -->
</x-app-layout>
