<?php
// StorePropertyRequest.php
namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agent_id'           => ['nullable', 'exists:agents,id'],
            'category_id'        => ['required', 'exists:property_categories,id'],
            'purpose'            => ['required', 'in:sale,rent,sale_rent'],
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
            'status'             => ['nullable', 'in:draft,active,inactive,sold,rented'],
            'rental_period'      => ['nullable','in:daily,monthly,yearly|required_if:purpose,rent'],
            'phone'              => ['nullable', 'string', 'max:30'],

            'rooms'              => ['nullable', 'integer', 'min:0'],
            'bedrooms'           => ['nullable', 'integer', 'min:0'],
            'bathrooms'          => ['nullable', 'integer', 'min:0'],
            'garages'            => ['nullable', 'integer', 'min:0'],
            'area_size'          => ['nullable', 'string', 'max:100'],
            'land_size'          => ['nullable', 'string', 'max:100'],
            'year_built'         => ['nullable', 'digits:4', 'integer', 'min:1800'],
            'furnishing'         => ['nullable', 'in:furnished,semi-furnished,unfurnished'],

            'currency'           => ['nullable', 'in:USD,KHR'],
            'price'              => ['nullable', 'numeric', 'min:0'],
            'price_label'        => ['nullable', 'string', 'max:100'],
            'price_negotiable'   => ['nullable', 'boolean'],

            'main_image'         => ['nullable', 'string', 'max:500'],
            'floor_plan_image'   => ['nullable', 'string', 'max:500'],
            'video_url'          => ['nullable', 'url', 'max:500'],
            'virtual_tour_url'   => ['nullable', 'url', 'max:500'],

            'area_id'            => ['nullable', 'exists:areas,id'],
            'address'            => ['nullable', 'string', 'max:500'],
            'latitude'           => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'          => ['nullable', 'numeric', 'between:-180,180'],

            'published_at'       => ['nullable', 'date'],
            'expires_at'         => ['nullable', 'date', 'after_or_equal:published_at'],

            'amenities'          => ['nullable', 'array'],
            'features'           => ['nullable', 'array'],
            'gallery_images'     => ['nullable', 'array'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'agent_id'           => $this->agent_id ?? null,
            'listed_by'          => auth()->id(),
            'category_id'        => $this->category_id,
            'purpose'            => $this->purpose,
            'title'              => $this->title,
            'description'        => $this->description ?? null,
            'notes'              => $this->notes ?? null,
            'status'             => $this->status ?? 'draft',
            'rental_period'      => $this->rental_period ?? null,
            'phone'              => $this->phone ?? null,

            'rooms'              => $this->rooms ?? null,
            'bedrooms'           => $this->bedrooms ?? 0,
            'bathrooms'          => $this->bathrooms ?? 0,
            'garages'            => $this->garages ?? 0,
            'area_size'          => $this->area_size ?? null,
            'land_size'          => $this->land_size ?? null,
            'year_built'         => $this->year_built ?? null,
            'furnishing'         => $this->furnishing ?? 'unfurnished',

            'currency'           => $this->currency ?? 'USD',
            'price'              => $this->price ?? null,
            'price_label'        => $this->price_label ?? null,
            'price_negotiable'   => $this->price_negotiable ?? false,

            'main_image'         => $this->main_image ?? null,
            'floor_plan_image'   => $this->floor_plan_image ?? null,
            'video_url'          => $this->video_url ?? null,
            'virtual_tour_url'   => $this->virtual_tour_url ?? null,

            'area_id'            => $this->area_id ?? null,
            'address'            => $this->address ?? null,
            'latitude'           => $this->latitude ?? null,
            'longitude'          => $this->longitude ?? null,

            'featured'           => false,
            'verified'           => false,
            'published_at'       => $this->published_at ?? null,
            'expires_at'         => $this->expires_at ?? null,

            'amenities'          => $this->amenities ?? [],
            'features'           => $this->features ?? [],
            'gallery_images'     => $this->gallery_images ?? [],
        ];
    }
}