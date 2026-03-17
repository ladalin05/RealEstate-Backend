<?php

namespace App\Models\Interaction;

use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;

class Inquiry extends Model
{
    protected $table = 'inquiries';

    protected $fillable = [
        'property_id',
        'user_id',
        'name',
        'email',
        'phone',
        'message'
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}