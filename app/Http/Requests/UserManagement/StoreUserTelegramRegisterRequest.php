<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserTelegramRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'telegram_id'     => 'required|string|max:255',
            'username'        => 'required|string|max:255',
            'profile_picture' => 'nullable|string|max:255',
        ];
    }
}