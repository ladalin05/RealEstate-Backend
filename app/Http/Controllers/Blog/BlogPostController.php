<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StoreBlogPostRequest;
use App\Http\Requests\Blog\UpdateBlogPostRequest;
use App\DataTables\Blog\BlogPostDataTable;
use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogPostSection;
use App\Models\Blog\BlogSectionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(BlogPostDataTable $dataTable)
    {
        return $dataTable->render('blog.blog-post.index');
    }

    public function create(Request $request)
    {
        try {

            if ($request->isMethod('post')) {
                $formRequest = app(StoreBlogPostRequest::class);
                $data = $formRequest->validated();

                DB::transaction(function () use ($data, $request) {
                    $post = BlogPost::create([
                        'uuid'             => Str::uuid(),
                        'category_id'      => $data['category_id'] ?? null,
                        'author_id'        => auth('admin')->id(),
                        'title'            => $data['title'],
                        'excerpt'          => $data['excerpt'] ?? null,
                        'overview'         => $data['overview'] ?? null,
                        'featured_image'   => $this->storeFeaturedImage($request),
                        'meta_title'       => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'status'           => $data['status'],
                        'published_at'     => $data['status'] === 'published'
                                                ? ($data['published_at'] ?? now())
                                                : ($data['published_at'] ?? null),
                    ]);

                    $post->tags()->sync($data['tags'] ?? []);

                    $this->storeSections($post, $data['sections'] ?? [], $request);
                });

                return $this->redirectResponse(
                    message: __('global.create_post_successfully'),
                    route: route('blog.posts.index'),
                );
            }

            return $this->modalResponse(
                title:  __('global.add_new'),
                view:   'blog.blog-post.form',
                data:   ['form' => new BlogPost()],
                action: route('blog.posts.add'),
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
            $post = BlogPost::findOrFail($request->id);

            if ($request->isMethod('post')) {
                $formRequest = app(UpdateBlogPostRequest::class);
                $data = $formRequest->validated();

                DB::transaction(function () use ($data, $request, $post) {
                    $featuredImage = $this->storeFeaturedImage($request) ?? $post->featured_image;

                    $post->update([
                        'category_id'      => $data['category_id'] ?? null,
                        'title'            => $data['title'],
                        'excerpt'          => $data['excerpt'] ?? null,
                        'overview'         => $data['overview'] ?? null,
                        'featured_image'   => $featuredImage,
                        'meta_title'       => $data['meta_title'] ?? null,
                        'meta_description' => $data['meta_description'] ?? null,
                        'status'           => $data['status'],
                        'published_at'     => $data['status'] === 'published'
                                                ? ($data['published_at'] ?? $post->published_at ?? now())
                                                : ($data['published_at'] ?? null),
                    ]);

                    $post->tags()->sync($data['tags'] ?? []);

                    $this->syncSections($post, $data['sections'] ?? [], $request);
                });

                return $this->redirectResponse(
                    message: __('global.updated_post_successfully'),
                    route: route('blog.posts.index'),
                );
            }

            return $this->modalResponse(
                title: __('global.edit'),
                view: 'blog.blog-post.form',
                data: ['form' => $post->load('tags', 'sections.images')],
                action: route('blog.posts.edit', ['id' => $post->id]),
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
            $post = BlogPost::findOrFail($request->id);
            $post->delete();

            return $this->redirectResponse(
                message: __('global.deleted_post_successfully'),
                route: route('blog.posts.index'),
            );
        } catch (\Throwable $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 500,
            );
        }
    }

    /**
     * Store the uploaded featured image, if any, and return its path.
     */
    private function storeFeaturedImage(Request $request): ?string
    {
        if ($request->hasFile('featured_image')) {
            return $request->file('featured_image')->store('blog/posts', 'public');
        }

        return null;
    }

    /**
     * Create sections + their images for a brand-new post.
     */
    private function storeSections(BlogPost $post, array $sections, Request $request): void
    {
        foreach ($sections as $index => $sectionData) {
            $section = BlogPostSection::create([
                'post_id'    => $post->id,
                'heading'    => $sectionData['heading'] ?? null,
                'content'    => $sectionData['content'] ?? null,
                'list_items' => isset($sectionData['list_items'])
                                    ? array_values($sectionData['list_items'])
                                    : null,
                'sort_order' => $sectionData['sort_order'] ?? $index,
            ]);

            $this->storeSectionImages($section, $request, $index);
        }
    }

    /**
     * Replace sections on update: delete removed ones, update/create the rest.
     */
    private function syncSections(BlogPost $post, array $sections, Request $request): void
    {
        $keepIds = array_filter(array_column($sections, 'id'));

        // Delete sections that were removed entirely (cascades to their images via FK)
        $post->sections()->whereNotIn('id', $keepIds)->delete();

        foreach ($sections as $index => $sectionData) {
            if (!empty($sectionData['id'])) {
                $section = BlogPostSection::where('post_id', $post->id)
                    ->findOrFail($sectionData['id']);

                $section->update([
                    'heading'    => $sectionData['heading'] ?? null,
                    'content'    => $sectionData['content'] ?? null,
                    'list_items' => isset($sectionData['list_items'])
                                        ? array_values($sectionData['list_items'])
                                        : null,
                    'sort_order' => $sectionData['sort_order'] ?? $index,
                ]);

                // Remove explicitly deleted images for this section
                if (!empty($sectionData['removed_image_ids'])) {
                    BlogSectionImage::where('section_id', $section->id)
                        ->whereIn('id', $sectionData['removed_image_ids'])
                        ->delete();
                }
            } else {
                $section = BlogPostSection::create([
                    'post_id'    => $post->id,
                    'heading'    => $sectionData['heading'] ?? null,
                    'content'    => $sectionData['content'] ?? null,
                    'list_items' => isset($sectionData['list_items'])
                                        ? array_values($sectionData['list_items'])
                                        : null,
                    'sort_order' => $sectionData['sort_order'] ?? $index,
                ]);
            }

            $this->storeSectionImages($section, $request, $index);
        }
    }

    /**
     * Store newly uploaded images for a given section index.
     */
    private function storeSectionImages(BlogPostSection $section, Request $request, int $index): void
    {
        $files = $request->file("sections.$index.images") ?? [];

        $startOrder = $section->images()->max('sort_order') + 1;

        foreach ($files as $position => $file) {
            $path = $file->store('blog/sections', 'public');

            BlogSectionImage::create([
                'section_id' => $section->id,
                'image_path' => $path,
                'sort_order' => $startOrder + $position,
            ]);
        }
    }
}