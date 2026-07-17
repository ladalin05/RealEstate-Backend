<?php

namespace App\Models\UserManagement;

use App\Models\Property\Property;
use App\Models\Interaction\RequestInfo;
use App\Models\Interaction\TourSchedule;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agents';

    protected $fillable = [
        'user_id',
        'agency_id',
        'license_number',
        'experience_years',
        'bio',
        'rating',
        'total_sales'
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

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'agent_id');
    }

    public function requestInfos()
    {
        return $this->hasMany(RequestInfo::class, 'agent_id');
    }

    public function tourSchedules()
    {
        return $this->hasMany(TourSchedule::class, 'agent_id');
    }
}