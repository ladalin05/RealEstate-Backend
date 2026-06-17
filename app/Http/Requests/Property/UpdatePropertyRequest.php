<?php
// UpdatePropertyRequest.php
namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\Property\Property;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id'            => 'required|exists:property_types,id',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'notes'              => 'nullable|string',
            'purpose'            => 'required|in:sale,rent,sale_rent',
            'status'             => 'required|in:draft,active,inactive,sold,rented',
            'rental_period'      => 'nullable|in:daily,monthly,yearly|required_if:purpose,rent',
            'phone'              => 'nullable|string|max:30',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $property = Property::findOrFail($this->route('id') ?? request()->id);

        return [
            // Core
            'type_id'            => $this->type_id,
            'title'              => addslashes($this->title),
            'slug'               => Str::slug($this->title, '-'),
            'description'        => $this->description ? addslashes($this->description) : null,
            'notes'              => $this->notes ?? null,
            'purpose'            => $this->purpose ?? 'sale',
            'status'             => $this->status  ?? $property->status,
            'rental_period'      => $this->rental_period ?? null,
            'phone'              => $this->phone ?? null,

            // Property details
            'rooms'              => $this->rooms        ?? null,
            'bathrooms'          => $this->bathrooms    ?? 0,
            'floors'             => $this->floors       ?? null,
            'floor_number'       => $this->floor_number ?? null,
            'area_size'          => $this->area_size    ?? null,
            'land_size'          => $this->land_size    ?? null,
            'year_built'         => $this->year_built   ?? null,
            'direction'          => $this->direction    ?? null,
            'furnishing'         => $this->furnishing   ?? 'unfurnished',
            'featured'           => $this->featured     ?? $property->featured,
            'verified'           => $this->verified     ?? $property->verified,

            // Pricing
            'currency'           => $this->currency         ?? $property->currency,
            'price'              => $this->price            ?? null,
            'price_label'        => $this->price_label      ?? null,
            'price_negotiable'   => $this->price_negotiable ?? $property->price_negotiable,

            // Media — fall back to whatever is already stored
            'main_image'         => $this->main_image       ?: $property->main_image,
            'floor_plan_image'   => $this->floor_plan_image ?: $property->floor_plan_image,
            'video_url'          => $this->video_url        ?? $property->video_url,
            'virtual_tour_url'   => $this->virtual_tour_url ?? $property->virtual_tour_url,
            'gallery_images'     => $this->gallery_images   ?? [],

            // Location
            'country_id'         => $this->country_id  ?? null,
            'province_id'        => $this->province_id ?? null,
            'district_id'        => $this->district_id ?? null,
            'commune_id'         => $this->commune_id  ?? null,
            'address'            => $this->address     ?? null,
            'latitude'           => $this->latitude    ?? null,
            'longitude'          => $this->longitude   ?? null,

            // Relations
            'amenities'          => $this->amenities ?? [],
            'features'           => $this->features  ?? [],
        ];
    }
}