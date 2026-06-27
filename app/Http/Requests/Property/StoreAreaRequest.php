<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'province_id' => ['nullable'],
            'district_id' => ['nullable'],
            'commune_id'  => ['nullable'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:areas,slug'],
            'image'       => ['nullable', 'string', 'max:255'],
            'status'      => ['required', 'string', 'max:255'],
        ];
    }
}