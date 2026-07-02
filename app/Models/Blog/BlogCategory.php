<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $fillable = [
        'name_en',
        'name_km',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}