<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
                            'web_name',
                            'web_logo',
                            'favicon',
                            'web_email',
                            'facebook',
                            'instagram',
                            'description',
                            'meta_title',
                            'meta_description'];
 
	
	 public $timestamps = false;
    
}
