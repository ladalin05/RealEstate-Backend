<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'status'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }
}