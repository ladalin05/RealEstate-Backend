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
        return [
            'province_id' => ['required'],
            'district_id' => ['required'],
            'commune_id'  => ['nullable'],
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255'],
            'image'       => ['nullable', 'string', 'max:255'],
            'status'      => ['required'],
        ];
    }
}