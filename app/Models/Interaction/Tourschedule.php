<?php

namespace App\Models\Interaction;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Admin;

class TourSchedule extends Model
{
    protected $table = 'tour_schedules';

    protected $fillable = [
        'property_id',
        'agent_id',
        'user_id',
        'name',
        'email',
        'phone',
        'tour_type',
        'requested_date',
        'requested_time',
        'message',
        'status',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'handled_at'     => 'datetime',
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

    public function handledBy()
    {
        return $this->belongsTo(Admin::class, 'handled_by');
    }
}