<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favourites';

    protected $fillable = ['user_id', 'property_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

	public $timestamps = false;  
	
}
