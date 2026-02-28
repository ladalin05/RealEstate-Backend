<?php

namespace App\Models\UserManagement;

use Illuminate\Http\Request;
use App\Models\Setting\Campus;
use App\Models\Setting\Faculty;
use App\Models\Setting\Program;
use App\Models\Setting\CampusUser;
use App\Models\Setting\DegreeUser;
use App\Models\Setting\Department;
use App\Models\Setting\StudyLevel;
use Spatie\Activitylog\LogOptions;
use App\Models\Setting\FacultyUser;
use App\Models\Setting\ProgramUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting\ScholarshipCategoryUser;
use App\Models\Setting\WarehouseUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, SoftDeletes;
	public $timestamps = true;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username',
		'display_name',
		'email',
		'email_verified_at',
		'password',
		'image',
		'locale',
		'enabled',
		'role_id',
		'he_scholarship',
		'scholarship_category',
		'remember_token',
	];


	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            if (!$model->forceDeleting) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

	public function isAdmin()
	{
		return $this->roles()->where('administrator', 1)->first();
	}
    
    public function getCreatedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    
    public function getUpdatedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
    
    public function getDeletedAtAttribute($value): string
    {
        return date('d/m/Y h:i A', strtotime($value));
    }
}
