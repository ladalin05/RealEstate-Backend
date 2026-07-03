<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTourScheduleStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by route middleware (admin panel auth)
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:confirmed,rejected'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'status'     => $this->status,
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ];
    }
}