<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plan';

    protected $fillable = ['plan_name','plan_days','plan_duration','plan_price'];


	public $timestamps = false; 
	 

	
}
