<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class PropertyCategory extends Model
{
    protected $table = 'property_categories';

    protected $fillable = ['type_name','type_image','type_slug','status'];
 
	
    public $timestamps = false;

	public function property()
	{
		return $this->hasMany(Property::class,'type_id','id');
	}

	public static function getTypeInfo($id,$field_name) 
    { 
		$info = PropertyCategory::where('status','1')->where('id',$id)->first();
		
		if($info)
		{
			return  $info->$field_name;
		}
		else
		{
			return  '';
		}
	}
    
}
