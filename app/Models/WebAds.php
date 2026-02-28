<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebAds extends Model
{
    protected $table = 'web_banner_ads';

    protected $fillable = ['home_top', 'home_bottom'];


	public $timestamps = false;   
	
}
