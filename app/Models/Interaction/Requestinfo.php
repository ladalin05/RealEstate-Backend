<?php

namespace App\Models\Interaction;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Admin;

class RequestInfo extends Model
{
    protected $table = 'request_infos';

    protected $fillable = [
        'property_id',
        'agent_id',
        'user_id',
        'name',
        'email',
        'phone',
        'role',
        'message',
        'status',
        'reply_message',
        'replied_by',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repliedBy()
    {
        return $this->belongsTo(Admin::class, 'replied_by');
    }
}