<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGallery extends Model
{
    protected $table = 'property_gallery';

    protected $fillable = ['post_id','image'];
 
	
    public $timestamps = false;

	public static function getPropertyGalleryInfo($id,$field_name) 
    { 
		$info = PropertyGallery::where('id',$id)->first();
		
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
