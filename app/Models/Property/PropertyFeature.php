<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    use HasFactory;

    protected $table = 'property_features';

    protected $fillable = [
        'property_id',
        'feature_id',
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
     * Feature relation
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}