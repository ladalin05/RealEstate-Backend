<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;

class PropertyViews extends Model
{
    protected $table = 'property_views';

    protected $fillable = ['user_id', 'property_id','views', 'date'];


	public $timestamps = false;  


	public static function getPostTotalViews($post_id,$post_type) 
    { 
    	$total = PropertyViews::where('post_id',$post_id)->where('post_type',$post_type)->sum('post_views');
		 
		return $total;
	}

	public static function getPostTotalDownload($post_id,$post_type) 
    { 
    	$total = PropertyViews::where('post_id',$post_id)->where('post_type',$post_type)->sum('post_download');
		 
		return $total;
	}
 
}
