<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    public $timestamps = false;

    protected $table = 'blog_tags';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Posts using this tag (via blog_post_tags pivot).
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            BlogPost::class,
            'blog_post_tags',
            'tag_id',
            'post_id'
        );
    }
}