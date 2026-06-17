<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'province_id',
        'name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_id');
    }
}