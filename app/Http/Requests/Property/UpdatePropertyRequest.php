<?php
// UpdatePropertyRequest.php
namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Property\Property;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
{
    protected ?Property $property = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function property(): Property
    {
        if (!$this->property) {
            $this->property = Property::findOrFail(
                $this->route('id') ?? $this->input('id')
            );
        }

        return $this->property;
    }

    public function rules(): array
    {
        return [
            'property_code'      => ['nullable', 'string', 'max:50', Rule::unique('properties', 'property_code')->ignore($this->property()->id)],
            'agent_id'           => ['nullable', 'exists:agents,id'],
            'category_id'        => ['required', 'exists:property_categories,id'],
            'purpose'            => ['required', 'in:sale,rent,sale_rent'],
            'title_en'           => ['required', 'string', 'max:255'],
            'title_kh'           => ['nullable', 'string', 'max:255'],
            'description_en'     => ['nullable', 'string'],
            'description_kh'     => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
            'status'             => ['required', 'in:draft,active,inactive,sold,rented'],
            'rental_period'      => ['nullable', 'in:daily,monthly,yearly', 'required_if:purpose,rent'],
            'phone'              => ['nullable', 'string', 'max:30'],

            'rooms'              => ['nullable', 'integer', 'min:0'],
            'bedrooms'           => ['nullable', 'integer', 'min:0'],
            'bathrooms'          => ['nullable', 'integer', 'min:0'],
            'garages'            => ['nullable', 'integer', 'min:0'],
            'garage_size'        => ['nullable', 'string', 'max:100'],
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
            'address_en'         => ['nullable', 'string', 'max:500'],
            'address_kh'         => ['nullable', 'string', 'max:500'],
            'latitude'           => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'          => ['nullable', 'numeric', 'between:-180,180'],

            'published_at'       => ['nullable', 'date'],
            'expires_at'         => ['nullable', 'date', 'after_or_equal:published_at'],

            'featured'           => ['nullable', 'boolean'],
            'verified'           => ['nullable', 'boolean'],

            'amenities'          => ['nullable', 'array'],
            'features'           => ['nullable', 'array'],
            'gallery_images'     => ['nullable', 'array'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $property = $this->property();

        return [
            'property_code'      => $this->property_code ?: $property->property_code,
            'agent_id'           => $this->agent_id ?? $property->agent_id,
            'category_id'        => $this->category_id,
            'purpose'            => $this->purpose,
            'title_en'           => $this->title_en,
            'title_kh'           => $this->title_kh ?? null,
            'description_en'     => $this->description_en ?? null,
            'description_kh'     => $this->description_kh ?? null,
            'notes'              => $this->notes ?? null,
            'status'             => $this->status ?? $property->status,
            'rental_period'      => $this->rental_period ?? null,
            'phone'              => $this->phone ?? null,

            'rooms'              => $this->rooms ?? null,
            'bedrooms'           => $this->bedrooms ?? $property->bedrooms,
            'bathrooms'          => $this->bathrooms ?? $property->bathrooms,
            'garages'            => $this->garages ?? $property->garages,
            'garage_size'        => $this->garage_size ?? null,
            'area_size'          => $this->area_size ?? null,
            'land_size'          => $this->land_size ?? null,
            'year_built'         => $this->year_built ?? null,
            'furnishing'         => $this->furnishing ?? $property->furnishing,

            'currency'           => $this->currency ?? $property->currency,
            'price'              => $this->price ?? null,
            'price_label'        => $this->price_label ?? null,
            'price_negotiable'   => $this->boolean('price_negotiable'),

            'main_image'         => $this->main_image ?: $property->main_image,
            'floor_plan_image'   => $this->floor_plan_image ?: $property->floor_plan_image,
            'video_url'          => $this->video_url ?? $property->video_url,
            'virtual_tour_url'   => $this->virtual_tour_url ?? $property->virtual_tour_url,

            'area_id'            => $this->area_id ?? $property->area_id,
            'address_en'         => $this->address_en ?? null,
            'address_kh'         => $this->address_kh ?? null,
            'latitude'           => $this->latitude ?? null,
            'longitude'          => $this->longitude ?? null,

            'featured'           => $this->boolean('featured'),
            'verified'           => $this->boolean('verified'),
            'published_at'       => $this->published_at ?? $property->published_at,
            'expires_at'         => $this->expires_at ?? $property->expires_at,

            'amenities'          => $this->amenities ?? [],
            'features'           => $this->features ?? [],
            'gallery_images'     => $this->gallery_images ?? [],
        ];
    }
}