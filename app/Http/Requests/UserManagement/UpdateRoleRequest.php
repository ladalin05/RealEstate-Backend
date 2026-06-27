<?php

namespace App\Http\Requests\UserManagement;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Role;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'        => ['required','string','max:255'],
            'name_kh'        => ['nullable','string','max:255'],
            'administrator'  => ['nullable','boolean'],
            'description'    => ['nullable','string','max:255'],
            'order'          => ['nullable','numeric'],
        ];
    }
}