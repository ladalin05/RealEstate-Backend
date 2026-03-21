<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'menu_id',
        'title',
        'link',
        'order',
        'parent_id' // if you added it
    ];

    // Each item belongs to a menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Parent (for nested menu)
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    // Children (sub menu)
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }
}