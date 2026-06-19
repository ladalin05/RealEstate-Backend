<?php
// StorePropertyRequest.php
namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agent_id'           => 'nullable|exists:agents,id',
            'type_id'            => 'required|exists:property_types,id',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'notes'              => 'nullable|string',
            'purpose'            => 'required|in:sale,rent,sale_rent',
            'status'             => 'nullable|in:draft,active,inactive,sold,rented',
            'rental_period'      => 'nullable|in:daily,monthly,yearly|required_if:purpose,rent',
            'phone'              => 'nullable|string|max:30',

            'rooms'              => 'nullable|integer|min:0',
            'bathrooms'          => 'nullable|integer|min:0',
            'floors'             => 'nullable|integer|min:0',
            'floor_number'       => 'nullable|integer|min:0',
            'area_size'          => 'nullable|string|max:100',
            'land_size'          => 'nullable|string|max:100',
            'year_built'         => 'nullable|digits:4|integer|min:1800',
            'direction'          => 'nullable|in:north,south,east,west,northeast,northwest,southeast,southwest',
            'furnishing'         => 'nullable|in:furnished,semi-furnished,unfurnished',

            'currency'           => 'nullable|in:USD,KHR',
            'price'              => 'nullable|numeric|min:0',
            'price_label'        => 'nullable|string|max:100',
            'price_negotiable'   => 'nullable|boolean',

            'main_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'floor_plan_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'video_url'          => 'nullable|url|max:500',
            'virtual_tour_url'   => 'nullable|url|max:500',

            'area_id'            => 'nullable|exists:areas,id',
            'address'            => 'nullable|string|max:500',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        return [
            'agent_id'           => $this->agent_id ?? null,
            'type_id'            => $this->type_id,
            'title'              => addslashes($this->title),
            'slug'               => Str::slug($this->title, '-'),
            'description'        => $this->description ? addslashes($this->description) : null,
            'notes'              => $this->notes ?? null,
            'purpose'            => $this->purpose ?? 'sale',
            'status'             => $this->status ?? 'draft',
            'rental_period'      => $this->rental_period ?? null,
            'phone'              => $this->phone ?? null,
            'rooms'              => $this->rooms ?? null,
            'bathrooms'          => $this->bathrooms ?? 0,
            'floors'             => $this->floors ?? null,
            'floor_number'       => $this->floor_number ?? null,
            'area_size'          => $this->area_size ?? null,
            'land_size'          => $this->land_size ?? null,
            'year_built'         => $this->year_built ?? null,
            'direction'          => $this->direction ?? null,
            'furnishing'         => $this->furnishing ?? 'unfurnished',
            'featured'           => $this->featured ?? false,
            'verified'           => $this->verified ?? false,
            'currency'           => $this->currency ?? 'USD',
            'price'              => $this->price ?? null,
            'price_label'        => $this->price_label ?? null,
            'price_negotiable'   => $this->price_negotiable ?? false,
            'main_image'         => $this->main_image ?? null,
            'floor_plan_image'   => $this->floor_plan_image ?? null,
            'video_url'          => $this->video_url ?? null,
            'virtual_tour_url'   => $this->virtual_tour_url ?? null,
            'gallery_images'     => $this->gallery_images ?? [],
            'area_id'            => $this->area_id ?? null,
            'address'            => $this->address ?? null,
            'latitude'           => $this->latitude ?? null,
            'longitude'          => $this->longitude ?? null,
            'amenities'          => $this->amenities ?? [],
            'features'           => $this->features ?? [],
        ];
    }
}