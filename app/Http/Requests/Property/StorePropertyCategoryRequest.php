<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // adjust if you're using policies/gates
    }

    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_km' => ['nullable', 'string', 'max:255'],
            'slug'    => ['required', 'string', 'max:255', 'alpha_dash', 'unique:property_categories,slug'],
            'image'   => ['nullable', 'string', 'url', 'max:2048'],
            'status'  => ['nullable', 'boolean'],
        ];
    }

}