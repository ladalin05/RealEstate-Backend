<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $areaId = $this->route('area')?->id ?? $this->route('area');
        
        return [
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'commune_id'  => ['nullable', 'integer', 'exists:communes,id'],

            'name_en'  => ['required', 'string', 'max:255'],
            'name_km'  => ['nullable', 'string', 'max:255'],

            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('areas', 'slug')->ignore($areaId),
            ],

            'image'    => ['nullable', 'string', 'max:500'],
            'zip_code' => ['nullable', 'string', 'max:10'],

            'status' => ['required', 'boolean'],
        ];
    }
}