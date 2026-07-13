<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAreaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'commune_id'  => ['nullable', 'integer', 'exists:communes,id'],

            'name_en'  => ['required', 'string', 'max:255'],
            'name_km'  => ['nullable', 'string', 'max:255'],

            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('areas', 'slug'),
            ],

            'image'    => ['nullable', 'string', 'max:500'],
            'zip_code' => ['nullable', 'string', 'max:10'],

            'status' => ['required', 'boolean'],
        ];
    }
}