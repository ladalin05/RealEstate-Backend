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

    public function getBlogDetail($id)
    {
        $blog = BlogPost::query()
            ->leftJoin('admins', 'admins.id', '=', 'blog_posts.author_id')
            ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
            ->whereNull('blog_posts.deleted_at')
            ->where('blog_posts.id', $id)
            ->where('blog_posts.status', 'published')
            ->select([
                'blog_posts.id',
                'blog_posts.title',
                'blog_posts.excerpt',
                'blog_posts.overview',
                'blog_posts.featured_image as image',
                'blog_posts.category_id',
                'blog_posts.comments_count',
                'blog_posts.views_count',
                'blog_posts.created_at',
                'admins.name as author_name',
                'admins.image as author_image',
                'blog_categories.name as category_name',
            ])
            ->first();

        if (!$blog) {
            abort(404, 'Blog post not found');
        }

        $sections = DB::table('blog_post_sections')
            ->where('post_id', $blog->id)
            ->orderBy('sort_order')
            ->get();

        $sectionIds = $sections->pluck('id');

        $sectionImages = DB::table('blog_section_images')
            ->whereIn('section_id', $sectionIds)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section_id');

        $blog->sections = $sections->map(function ($section) use ($sectionImages) {
            return [
                'heading' => $section->heading,
                'content' => $section->content,
                'list' => $section->list_items ? json_decode($section->list_items, true) : null,
                'images' => isset($sectionImages[$section->id])
                    ? $sectionImages[$section->id]->pluck('image_path')->values()
                    : [],
            ];
        })->values();

        $blog->tags = DB::table('blog_tags')
            ->join('blog_post_tags', 'blog_tags.id', '=', 'blog_post_tags.tag_id')
            ->where('blog_post_tags.post_id', $blog->id)
            ->pluck('blog_tags.name');

        $relatedBlogs = BlogPost::query()
            ->leftJoin('admins', 'admins.id', '=', 'blog_posts.author_id')
            ->leftJoin('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
            ->whereNull('blog_posts.deleted_at')
            ->where('blog_posts.status', 'published')
            ->where('blog_posts.category_id', $blog->category_id)
            ->where('blog_posts.id', '!=', $id)
            ->orderBy('blog_posts.created_at', 'desc')
            ->select([
                'blog_posts.id',
                'blog_posts.title',
                'blog_posts.excerpt',
                'blog_posts.featured_image as image',
                'blog_posts.created_at',
                'admins.name as author_name',
                'admins.image as author_image',
                'blog_categories.name as category_name',
            ])
            ->limit(3)
            ->get();

        return [
            'blog' => $blog,
            'related_blogs' => $this->transformBlogs($relatedBlogs),
        ];
    }

    public function transformBlogs($blogs)
    { 
        return $blogs->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'description' => $blog->excerpt,
                'image' => $blog->image,
                'overview' => $blog->overview,
                'date' => $blog->created_at,
                'since_posted' => sincePosted($blog->created_at),
                'author' => $blog->author_name,
                'author_image' => $blog->author_image,
                'category' => $blog->category_name,
            ];
        })->values();
    }
}
