<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('district');

        return [
            'name'        => 'required|string|max:255|unique:districts,name,' . $id,
            'province_id' => 'required|integer|exists:provinces,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'District name is required.',
            'name.unique'          => 'This district name already exists.',
            'province_id.required' => 'Please select a province.',
            'province_id.exists'   => 'The selected province does not exist.',
        ];
    }
}