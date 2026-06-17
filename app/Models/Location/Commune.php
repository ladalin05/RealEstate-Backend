<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'district_id',
        'province_id',
        'name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}