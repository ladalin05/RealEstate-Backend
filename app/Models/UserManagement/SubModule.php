<?php

namespace App\Models\UserManagement;

use App\Models\UserManagement\Page;
use App\Models\UserManagement\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubModule extends Model
{
    use SoftDeletes;
    protected $table = 'sub_modules';
    protected $fillable = [
        'module_id',
        'type',
        'name_en',
        'name_kh',
        'slug',
        'order',
        'icon',
    ];


    function depart()
    {
        return $this->hasOne(Department::class, "id", "dept_id");
    }
    function module()
    {
        return $this->belongsTo(Module::class);
    }
    function pages()
    {
        return $this->hasMany(Page::class,  "sub_module_id");
    }
}
