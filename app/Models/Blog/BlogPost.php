<?php

namespace App\Models\Blog;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id',
        'author_id',
        'uuid',
        'title',
        'excerpt',
        'overview',
        'featured_image',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
    ];

    protected $casts = [
        'category_id'      => 'integer',
        'author_id'         => 'integer',
        'comments_count'    => 'integer',
        'views_count'       => 'integer',
        'published_at'      => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    // Read-only cached counters — don't let mass assignment touch these
    protected $guarded = [
        'id',
        'comments_count',
        'views_count',
    ];

    public function sections()
    {
        return $this->hasMany(BlogPostSection::class, 'post_id')->orderBy('sort_order');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags', 'post_id', 'tag_id');
    }

    /**
     * Auto-generate a UUID when creating a new post.
     */
    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->uuid)) {
                $post->uuid = (string) Str::uuid();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (fallback logic from comments)
    |--------------------------------------------------------------------------
    */

    public function getMetaTitleAttribute(?string $value): string
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute(?string $value): ?string
    {
        return $value ?: $this->excerpt;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                      ->whereNotNull('published_at')
                      ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', 'archived');
    }

    public function scopeForCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}