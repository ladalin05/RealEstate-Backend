<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agentId = $this->route('agent')?->id ?? $this->route('id');

        return [
            'email' => ['sometimes', 'required', 'email', 'max:191', Rule::unique('agents', 'email')->ignore($agentId), ],
            'password' => ['sometimes', 'nullable', 'string', Password::min(8)->mixedCase()->numbers()],
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'required', 'string', 'max:100'],
            'phone' => [ 'sometimes', 'nullable', 'string', 'max:30', 'regex:/^\+[1-9]\d{6,14}$/', Rule::unique('agents', 'phone')->ignore($agentId), ],
            'profile_image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gender' => ['sometimes', 'nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => ['sometimes', 'nullable', 'date', 'before:today'],
            'license_number' => ['sometimes', 'nullable', 'string', 'max:100', Rule::unique('agents', 'license_number')->ignore($agentId), ],
            'license_expires_at' => ['sometimes', 'nullable', 'date'],
            'experience_years' => ['sometimes', 'integer', 'min:0', 'max:127'],
            'specializations' => ['sometimes', 'nullable', 'array'],
            'specializations.*' => ['string', 'max:100'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'certifications' => ['sometimes', 'nullable', 'array'],
            'certifications.*.name' => ['required_with:certifications', 'string', 'max:150'],
            'certifications.*.issuer' => ['nullable', 'string', 'max:150'],
            'certifications.*.year' => ['nullable', 'integer', 'digits:4', 'min:1950', 'max:' . (date('Y') + 1)],
            'social_links' => ['sometimes', 'nullable', 'array'],
            'social_links.facebook' => ['nullable', 'url', 'max:255'],
            'social_links.instagram' => ['nullable', 'url', 'max:255'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.youtube' => ['nullable', 'url', 'max:255'],
            'social_links.tiktok' => ['nullable', 'url', 'max:255'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.whatsapp' => ['nullable', 'string', 'max:30'],
            'social_links.telegram' => ['nullable', 'string', 'max:100'],
            'social_links.line' => ['nullable', 'string', 'max:100'],
            'social_links.wechat' => ['nullable', 'string', 'max:100'],
            'social_links.website' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone must be in E.164 format, e.g. +85512345678.',
            'certifications.*.name.required_with' => 'Each certification needs a name.',
        ];
    }
}