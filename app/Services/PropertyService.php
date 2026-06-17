<?php

namespace App\Services;

use App\Models\Property\Property;
use App\Models\Property\PropertyGallery;
use App\Models\Property\PropertyFeature;
use App\Models\Property\PropertyAmenity;
use App\Models\Location\PropertyLocation;

class PropertyService extends BaseService
{
    protected function getQuery()
    {
        return Property::query();
    }

    // ─── Public API ───────────────────────────────────────────────────────────

    public function create(array $params = []): Property
    {
        [$propertyData, $relations] = $this->splitData($params);

        $property = parent::create($propertyData);

        $this->saveGallery($property->id, $relations['gallery_images']);
        $this->saveLocation($property->id, $relations);
        $this->syncAmenities($property->id, $relations['amenities']);
        $this->syncFeatures($property->id, $relations['features']);

        return $property;
    }

    public function update(array $params = [], string $id = null): Property
    {
        [$propertyData, $relations] = $this->splitData($params);

        $property = parent::update($propertyData, $id);

        // Gallery: always replace so removed images are reflected
        PropertyGallery::where('property_id', $property->id)->delete();
        $this->saveGallery($property->id, $relations['gallery_images']);

        $this->saveLocation($property->id, $relations, update: true);

        // Amenities & features: always sync so deselections take effect
        $this->syncAmenities($property->id, $relations['amenities']);
        $this->syncFeatures($property->id, $relations['features']);

        return $property;
    }

    // ─── Data splitting ───────────────────────────────────────────────────────

    private function splitData(array $params): array
    {
        $relationKeys = [
            'gallery_images',
            'amenities',
            'features',
            'country_id',
            'province_id',
            'district_id',
            'commune_id',
            'address',
            'latitude',
            'longitude',
        ];

        $relations = array_merge(
            // Defaults so callers never get undefined-key errors
            ['gallery_images' => [], 'amenities' => [], 'features' => []],
            array_intersect_key($params, array_flip($relationKeys))
        );

        $propertyData = array_diff_key($params, array_flip($relationKeys));

        return [$propertyData, $relations];
    }

    // ─── Gallery ──────────────────────────────────────────────────────────────

    private function saveGallery(int $propertyId, array $images): void
    {
        if (empty($images)) return;

        $rows = array_map(
            fn(string $image) => ['property_id' => $propertyId, 'image' => $image],
            $images
        );

        PropertyGallery::insert($rows); // single query instead of N queries
    }

    // ─── Location ─────────────────────────────────────────────────────────────

    private function saveLocation(int $propertyId, array $data, bool $update = false): void
    {
        $location = [
            'country_id'  => $data['country_id']  ?? null,
            'province_id' => $data['province_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'commune_id'  => $data['commune_id']  ?? null,
            'address'     => $data['address']     ?? null,
            'latitude'    => $data['latitude']    ?? null,
            'longitude'   => $data['longitude']   ?? null,
        ];

        // Skip if nothing useful was submitted at all
        if (!array_filter($location)) return;

        PropertyLocation::updateOrCreate(
            ['property_id' => $propertyId],
            $location
        );
    }

    // ─── Amenities ────────────────────────────────────────────────────────────

    private function syncAmenities(int $propertyId, array $amenityIds): void
    {
        PropertyAmenity::where('property_id', $propertyId)->delete();

        if (empty($amenityIds)) return;

        $rows = array_map(
            fn($id) => ['property_id' => $propertyId, 'amenity_id' => (int) $id],
            $amenityIds
        );

        PropertyAmenity::insert($rows);
    }

    // ─── Features ─────────────────────────────────────────────────────────────

    private function syncFeatures(int $propertyId, array $featureIds): void
    {
        PropertyFeature::where('property_id', $propertyId)->delete();

        if (empty($featureIds)) return;

        $rows = array_map(
            fn($id) => ['property_id' => $propertyId, 'feature_id' => (int) $id],
            $featureIds
        );

        PropertyFeature::insert($rows);
    }
}