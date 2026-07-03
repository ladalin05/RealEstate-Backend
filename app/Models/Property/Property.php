<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'properties';

    protected $fillable = [
        'property_code',
        'agent_id',
        'listed_by',
        'category_id',
        'purpose',
        'title_en',
        'title_kh',
        'description_en',
        'description_kh',
        'rooms',
        'bedrooms',
        'garages',
        'garage_size',
        'bathrooms',
        'area_size',
        'land_size',
        'furnishing',
        'year_built',
        'price',
        'currency',
        'price_negotiable',
        'price_label',
        'rental_period',
        'phone',
        'main_image',
        'floor_plan_image',
        'video_url',
        'virtual_tour_url',
        'area_id',
        'address_en',
        'address_kh',
        'latitude',
        'longitude',
        'notes',
        'verified',
        'featured',
        'status',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'latitude'         => 'decimal:7',
        'longitude'        => 'decimal:7',
        'price_negotiable' => 'boolean',
        'verified'         => 'boolean',
        'featured'         => 'boolean',
        'published_at'     => 'datetime',
        'expires_at'       => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function listedBy()
    {
        return $this->belongsTo(Admin::class, 'listed_by');
    }

    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'category_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities', 'property_id', 'amenity_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'property_features', 'property_id', 'feature_id');
    }

    public function property_image()
    {
        return $this->hasMany(PropertyGallery::class, 'property_id', 'id');
    }
}