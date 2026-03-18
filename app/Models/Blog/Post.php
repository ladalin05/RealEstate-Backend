<?php

namespace App\Models\Blog;

use App\Models\UserManagement\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'author_id',
        'featured_image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}