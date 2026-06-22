<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPostSection extends Model
{
    public $timestamps = false;

    protected $table = 'blog_post_sections';

    protected $fillable = [
        'post_id',
        'heading',
        'content',
        'list_items',
        'sort_order',
    ];

    protected $casts = [
        'list_items' => 'array', // longtext + json_valid check
        'sort_order' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    /**
     * Images attached to this section, ordered.
     */
    public function images(): HasMany
    {
        return $this->hasMany(BlogSectionImage::class, 'section_id')
            ->orderBy('sort_order');
    }
}