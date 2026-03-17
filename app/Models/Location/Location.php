<?php

namespace App\Models\Location;

use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = ['name', 'status']; 
	
    public $timestamps = false;

	public function locationproperty()
	{
		return $this->hasMany(Property::class,'location_id','id');
	}

	public static function getLocationInfo($id,$field_name) 
    { 
		$info = Location::where('id',$id)->first();
		
		if($info)
		{
			return  $info->$field_name;
		}
		else
		{
			return  '';
		}
	}
	
	public static function getPropertyDetails($id) 
    { 
		$info = Property::where('id',$id)->first();
		
		return  $info;
	}
 
}
