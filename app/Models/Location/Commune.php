<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'status'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}