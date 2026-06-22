<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BlogSectionImage extends Model
{
    public $timestamps = false;

    protected $table = 'blog_section_images';

    protected $fillable = [
        'section_id',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(BlogPostSection::class, 'section_id');
    }

    /**
     * Public URL for the stored image.
     */
    public function getUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}