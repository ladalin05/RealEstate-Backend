<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class PropertyViews extends Model
{
    protected $table = 'property_views';

    protected $fillable = ['property_id', 'user_id', 'view_count', 'viewed_date'];

    protected $casts = [
        'viewed_date' => 'date',
    ];

    public $timestamps = true; // created_at / updated_at exist in the table

    public static function getPropertyTotalViews($property_id)
    {
        return self::where('property_id', $property_id)->sum('view_count');
    }
}