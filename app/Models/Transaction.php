<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';

    protected $fillable = ['email', 'plan_id','gateway','payment_id'];


	public $timestamps = false;
 
	  
}
