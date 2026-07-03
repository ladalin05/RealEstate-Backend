<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequestInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by route middleware (admin panel auth)
    }

    public function rules(): array
    {
        return [
            'reply_message' => ['required', 'string'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'reply_message' => $this->reply_message,
            'status'        => 'replied',
            'replied_by'    => auth()->id(),
            'replied_at'    => now(),
        ];
    }
}