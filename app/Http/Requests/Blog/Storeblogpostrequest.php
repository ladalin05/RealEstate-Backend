<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['nullable', 'integer', 'exists:blog_categories,id'],
            'title'               => ['required', 'string', 'max:255'],
            'excerpt'             => ['nullable', 'string', 'max:500'],
            'overview'            => ['nullable', 'string'],
            'featured_image'      => ['nullable', 'string', 'max:500'],
            'meta_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'    => ['nullable', 'string', 'max:500'],
            'status'              => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at'        => ['nullable', 'date'],

            'tags'                => ['nullable', 'array'],
            'tags.*'              => ['integer', 'exists:blog_tags,id'],
            'new_tags'            => ['nullable', 'string'],

            'sections'                          => ['nullable', 'array'],
            'sections.*.heading'                => ['nullable', 'string', 'max:500'],
            'sections.*.content'                => ['nullable', 'string'],
            'sections.*.list_items'             => ['nullable', 'array'],
            'sections.*.list_items.*'           => ['string', 'max:1000'],
            'sections.*.sort_order'             => ['nullable', 'integer', 'min:0'],
            'sections.*.images'                 => ['nullable', 'array'],
            'sections.*.images.*'               => ['string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'  => 'The post title is required.',
            'status.required' => 'Please choose a status for this post.',
        ];
    }
}