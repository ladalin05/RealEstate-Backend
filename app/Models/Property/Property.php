<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Location\Location;
use App\Models\Location\PropertyLocation;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'properties';

    protected $fillable = [
        'user_id',
        'type_id',
        'title',
        'slug',
        'description',
        'phone',
        'purpose',
        'bedrooms',
        'bathrooms',
        'area',
        'furnishing',
        'price',
        'verified',
        'featured',
        'status',
        'main_image',
        'floor_plan_image'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, 'type_id');
    }

    public function location()
    {
        return $this->hasOne(PropertyLocation::class, 'property_id', 'id');
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
