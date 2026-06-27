<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogCategoryRequest;
use App\Http\Requests\Blog\UpdateBlogCategoryRequest;
use App\DataTables\Blog\BlogCategoryDataTable;
use App\Models\Blog\BlogCategory;
use App\Services\BaseService;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    private BaseService $service;
    public function __construct()
    {
        $this->service = new class extends BaseService {
            protected function getQuery() { return BlogCategory::query(); }
        };
    }

    public function index(BlogCategoryDataTable $dataTable)
    {
        return $dataTable->render('blog.blog-category.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreBlogCategoryRequest::class);
                $this->service->create($formRequest->validated());

                return $this->redirectResponse(
                    message: __('messages.create_blog_category_successfully'),
                    route: route('blogs.categories.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'blog.blog-category.form',
                data:   ['form' => new BlogCategory()],
                action: route('blogs.categories.add'),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $category = BlogCategory::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateBlogCategoryRequest::class);
                $this->service->update($formRequest->validated(), $category->id);

                return $this->redirectResponse(
                    message: __('messages.update_blog_category_successfully'),
                    route: route('blogs.categories.index'),
                );
            }

            return $this->modalResponse(
                title: __('global.edit'),
                view: 'blog.blog-category.form',
                data: ['form' => $category],
                action: route('blogs.categories.edit', ['id' => $category->id]),
            );

        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    public function delete(Request $request)
    {
        try {
            $category = BlogCategory::findOrFail($request->id);
            $category->delete();

            return $this->redirectResponse(
                message: __('messages.delete_blog_category_successfully'),
                route: route('blogs.categories.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }
}