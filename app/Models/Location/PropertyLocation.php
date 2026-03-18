<?php

namespace App\Models\Location;

use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLocation extends Model
{
    use HasFactory;

    protected $table = 'property_locations';

    // Fillable fields for mass assignment
    protected $fillable = [
        'property_id',
        'country_id',
        'city_id',
        'district_id',
        'commune_id',
        'address',
        'latitude',
        'longitude',
    ];

    // Relationships

    /**
     * Property that this location belongs to
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Country relation
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * City relation
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * District relation
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Commune relation
     */
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}