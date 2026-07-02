<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'        => ['required', 'string', 'max:100'],
            'name_km'        => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:100', 'unique:blog_categories,slug'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'boolean'],
        ];
    }
}