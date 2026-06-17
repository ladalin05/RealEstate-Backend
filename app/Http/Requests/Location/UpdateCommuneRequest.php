<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommuneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('commune');

        return [
            'name'        => 'required|string|max:255|unique:communes,name,' . $id,
            'province_id' => 'required|integer|exists:provinces,id',
            'district_id' => 'required|integer|exists:districts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Commune name is required.',
            'name.unique'          => 'This commune name already exists.',
            'province_id.required' => 'Please select a province.',
            'province_id.exists'   => 'The selected province does not exist.',
            'district_id.required' => 'Please select a district.',
            'district_id.exists'   => 'The selected district does not exist.',
        ];
    }
}