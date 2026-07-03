<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public endpoint — guests and logged-in users can both send an inquiry
    }

    public function rules(): array
    {
        return [
            'property_id' => ['nullable', 'exists:properties,id'],
            'agent_id'    => ['nullable', 'exists:agents,id'],
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'role'        => ['nullable', 'in:buyer,tenant,agent,other'],
            'message'     => ['required', 'string'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'property_id' => $this->property_id ?? null,
            'agent_id'    => $this->agent_id ?? null,
            'user_id'     => auth('api')->id(),
            'name'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone ?? null,
            'role'        => $this->role ?? null,
            'message'     => $this->message,
            'status'      => 'new',
        ];
    }
}