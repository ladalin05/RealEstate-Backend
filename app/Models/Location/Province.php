<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'alt_name',
        'geom'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}