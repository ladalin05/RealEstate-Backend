<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'     => ['required','string','max:255'],
            'username' => ['required','string','max:255','unique:users,username'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
            'phone'    => ['nullable','string','max:255'],
            'gender'   => ['nullable','in:male,female,others'],
            'dob'      => ['nullable','date'],
        ];
    }

    /**
     * Custom error messages (optional, adjust as you like).
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken.',
            'email.unique'    => 'This email is already registered.',
        ];
    }
}