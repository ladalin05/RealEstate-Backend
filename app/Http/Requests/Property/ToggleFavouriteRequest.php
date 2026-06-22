<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavouriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'property_id' => ['required', 'integer', 'exists:properties,id'],
        ];
    }
}