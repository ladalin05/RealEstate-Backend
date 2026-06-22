<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogPostRequest;
use App\Http\Requests\Blog\UpdateBlogPostRequest;
use App\DataTables\Blog\BlogPostDataTable;
use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\BlogTag;
use App\Services\BlogPostService;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function __construct(protected BlogPostService $service) {}

    public function index(BlogPostDataTable $dataTable)
    {
        return $dataTable->render('blog.blog-post.index');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $formRequest = app(StoreBlogPostRequest::class);
            $this->service->create($formRequest->validated());

            return $this->redirectResponse(
                message: __('global.create_post_successfully'),
                route: route('blogs.posts.index'),
            );
        }

        return $this->viewResponse(
            view:   'blog.blog-post.form',
            data:   [
                'form'       => new BlogPost(),
                'categories' => BlogCategory::orderBy('name')->get(),
                'tags'       => BlogTag::orderBy('name')->get(),
            ],
            action: route('blogs.posts.add'),
        );
    }

    public function update(Request $request)
    {
        $post = BlogPost::findOrFail($request->id);

        if ($request->isMethod('post')) {
            $formRequest = app(UpdateBlogPostRequest::class);
            $this->service->update($formRequest->validated(), $post->id);

            return $this->redirectResponse(
                message: __('global.update_post_successfully'),
                route: route('blogs.posts.index'),
            );
        }
        
        $post->load(['sections.images', 'tags']);

        return view('blog.blog-post.form', [
            'page_title' => __('global.update_post'),
            'isEdit'     => true,
            'post'       => $post,
            'categories' => BlogCategory::orderBy('name')->get(),
            'tags'       => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function delete(Request $request)
    {
        $post = BlogPost::findOrFail($request->id);
        $post->delete();

        return $this->redirectResponse(
            message: __('global.deleted_post_successfully'),
            route: route('blog.blog-post.index'),
        );
    }
}