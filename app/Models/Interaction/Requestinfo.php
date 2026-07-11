<?php

namespace App\Models\Interaction;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;

class RequestInfo extends Model
{
    protected $fillable = [
        'property_id',
        'agent_id',
        'user_id',
        'name',
        'email',
        'phone',
        'role',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(RequestMessage::class)
                    ->orderBy('created_at');
    }
}