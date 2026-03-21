<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favourite';

    protected $fillable = ['user_id', 'property_id'];


	public $timestamps = false;  
	
}
