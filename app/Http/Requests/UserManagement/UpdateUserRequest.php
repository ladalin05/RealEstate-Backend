<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Works whether the route param is {user} (model binding) or {id}
        $userId = auth('api')->id();

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'username' => [ 'sometimes', 'nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($userId) ],
            'email' => [ 'sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId) ],
            'password' => ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'profile_picture' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:255'],
            'gender' => ['sometimes', 'nullable', Rule::in(['male', 'female', 'others'])],
            'dob' => ['sometimes', 'nullable', 'date', 'before:today'],
            'google_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            'telegram_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            'telegram_username' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_verify_google' => ['sometimes', 'boolean'],
            'is_verify_telegram' => ['sometimes', 'boolean'],
            'is_verify_email' => ['sometimes', 'boolean'],
            'is_verify_phone' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'nullable', 'integer', Rule::in([0, 1])],
        ];
    }

    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}