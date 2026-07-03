<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public endpoint — guests and logged-in users can both book a tour
    }

    public function rules(): array
    {
        return [
            'property_id'     => ['required', 'exists:properties,id'],
            'agent_id'        => ['nullable', 'exists:agents,id'],
            'name'            => ['required', 'string', 'max:150'],
            'email'           => ['required', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'tour_type'       => ['required', 'in:in-person,video-chat'],
            'requested_date'  => ['required', 'date', 'after_or_equal:today'],
            'requested_time'  => ['required', 'date_format:H:i'],
            'message'         => ['nullable', 'string'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'property_id'     => $this->property_id,
            'agent_id'        => $this->agent_id ?? null,
            'user_id'         => auth('api')->id(),
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone ?? null,
            'tour_type'       => $this->tour_type,
            'requested_date'  => $this->requested_date,
            'requested_time'  => $this->requested_time,
            'message'         => $this->message ?? null,
            'status'          => 'pending',
        ];
    }
}