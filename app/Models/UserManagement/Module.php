<?php

namespace App\Models\UserManagement;

use App\Models\UserManagement\SubModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
	use SoftDeletes;
    protected $fillable = ['name_en', 'name_kh', 'slug', 'order', 'icon'];
    protected $table = "modules";
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('order');
    }
    public function sub_modules()
    {
        return $this->hasMany(SubModule::class)->orderBy('order');
    }
}
