<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProvinceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('province');

        return [
            'name'       => 'required|string|max:255|unique:provinces,name,' . $id,
            'alt_name'   => 'nullable|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Province name is required.',
            'name.unique'         => 'This province name already exists.',
            'country_id.required' => 'Please select a country.',
            'country_id.exists'   => 'The selected country does not exist.',
        ];
    }
}