<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\Agent;

class Agency extends Model
{
    protected $table = 'agencies';

    protected $fillable = [
        'name',
        'logo',
        'phone',
        'email',
        'website',
        'address',
        'description'
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}