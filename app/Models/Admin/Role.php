<?php

namespace App\Models\Admin;

use App\Models\Setting\Campus;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

	protected $table = "roles";
	protected $fillable = [
		'name_en',
		'name_kh',
		'description',
		'administrator',
		'order',
	];
	protected static $logAttributes = [
		'name_en',
		'name_kh',
		'description',
		'administrator',
		'order',
	];

	public $timestamps = true;

	public function scopeCampus($query)
	{
		if (!Auth::user()->isAdmin()) {
			return $query->join('role_permissions')->groupBy('roles.id')->select('roles.*');
		}
	}

	public function user()
	{
        return $this->belongsToMany(Admin::class, 'admin_roles', 'role_id', 'admin_id');
	}

	public function modules()
	{
		return $this->belongsToMany(Module::class)->withTimestamps();
	}

	public function rolePermissions()
	{
		return $this->hasMany(RolePermission::class, 'role_id', 'id');
	}

	public function isAdmin()
	{
		return $this->slug == 'administrator' ? true : false;
	}
	public function isFull()
	{
		return $this->slug == 'administrator' || $this->scope == 'full' ? true : false;
	}

}
