<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}