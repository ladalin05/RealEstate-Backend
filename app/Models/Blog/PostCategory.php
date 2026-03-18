<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}