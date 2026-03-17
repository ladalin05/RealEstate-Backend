<?php

namespace App\Models\Interaction;

use App\Models\Property\Property;
use App\Models\UserManagement\Agent;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;


class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'agent_id',
        'property_id',
        'rating',
        'comment'
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}