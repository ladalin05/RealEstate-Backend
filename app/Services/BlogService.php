<?php

namespace App\Services;

use App\Models\Blog\BlogPost;
use App\Traits\FormatsDataCard;
use App\Repositories\PropertyRepository;
use Illuminate\Support\Facades\DB;

class BlogService
{
    use FormatsDataCard;

    public function __construct()
    {}

    public function getBlogsData()
    {
        return [
            'blogs' => $this->getBlogs()
        ];
    }

    public function getBlogs()
    {
        $blogs = BlogPost::query()
            ->LeftJoin('agents', 'agents.id', '=', 'blog_posts.author_id')
            ->LeftJoin('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
            ->whereNull('blog_posts.deleted_at')
            ->orderBy('blog_posts.created_at', 'desc')
            ->select([
                'blog_posts.id',
                'blog_posts.title',
                'blog_posts.excerpt',
                'blog_posts.overview',
                'blog_posts.featured_image as image',
                'blog_posts.created_at',
                DB::raw('concat(agents.first_name, " ", agents.last_name) as author_name'),
                'agents.profile_image as author_image',
                'blog_categories.name as category_name',
            ])
            ->get();
        return $this->transformBlogs($blogs);
    }

    public function transformBlogs($blogs)
    {
        return $blogs->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'excerpt' => $blog->excerpt,
                'image' => $blog->image,
                'overview' => $blog->overview,
                'created_at' => $blog->created_at,
                'since_posted' => sincePosted($blog->created_at),
                'author_name' => $blog->author_name,
                'author_image' => $blog->author_image,
                'category_name' => $blog->category_name,
            ];
        })->values();
    }
}
