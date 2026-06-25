<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'commune_id'  => 'nullable|exists:communes,id',
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:areas,slug',
            'status'      => 'nullable|boolean',
        ];
    }
}