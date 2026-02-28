<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RolePermission extends Model
{
	//
	protected $table = "role_permissions";
	public $timestamps = false;
	protected $fillable = [
		'role_id',
		'campus_id',
		'action_id',
		'created_at',
	];
	public function role()
	{
		return $this->belongsTo(Role::class, 'role_id', 'id');
	}
}
