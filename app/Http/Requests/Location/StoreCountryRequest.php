<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:150|unique:countries,name',
            'code'   => 'nullable|string|max:5|unique:countries,code',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Country name is required.',
            'name.unique'     => 'This country name already exists.',
            'code.unique'     => 'This ISO code is already in use.',
            'code.max'        => 'ISO code must not exceed 5 characters.',
            'status.required' => 'Status is required.',
        ];
    }
}