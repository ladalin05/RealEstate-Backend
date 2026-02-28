<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pages';


    protected $fillable = ['page_title', 'page_slug','page_content'];
 
	 public $timestamps = false;
}
