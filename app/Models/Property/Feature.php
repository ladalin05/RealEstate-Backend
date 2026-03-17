<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name_en',
        'name_kh',
        'icon',
        'status'
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_features');
    }
}