<?php

namespace App\Models\Property;

use App\Models\Property\Amenity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $table = 'property_amenities';

    protected $fillable = [
        'property_id',
        'amenity_id',
    ];

    public $timestamps = false; // only created_at exists

    /**
     * Property relation
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Amenity relation
     */
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }
}