<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class PropertyLocation extends Model
{
    public $timestamps  = false;
    protected $primaryKey = 'property_id';

    protected $fillable = [
        'property_id',
        'country_id',
        'province_id',
        'district_id',
        'commune_id',
        'address',
        'latitude',
        'longitude',
        'map_embed',
    ];

    public function property() { return $this->belongsTo(Property::class, 'property_id'); }
    public function country()  { return $this->belongsTo(Country::class,  'country_id');  }
    public function province() { return $this->belongsTo(Province::class, 'province_id'); }
    public function district() { return $this->belongsTo(District::class, 'district_id'); }
    public function commune()  { return $this->belongsTo(Commune::class,  'commune_id');  }
}