<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'name'
    ];

    // One menu has many menu items
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('order');
    }
}