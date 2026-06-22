<?php

namespace App\Services;

use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogPostSection;
use App\Models\Blog\BlogSectionImage;
use App\Models\Blog\BlogTag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostService
{
    public function create(array $data): BlogPost
    {
        return DB::transaction(function () use ($data) {
            $post = new BlogPost();
            $post->category_id      = $data['category_id'] ?? null;
            $post->author_id        = auth()->id();
            $post->title            = $data['title'];
            $post->excerpt          = $data['excerpt'] ?? null;
            $post->overview         = $data['overview'] ?? null;
            $post->meta_title       = $data['meta_title'] ?? null;
            $post->meta_description = $data['meta_description'] ?? null;
            $post->status           = $data['status'];
            $post->published_at     = $data['status'] === 'published'
                ? ($data['published_at'] ?? now())
                : ($data['published_at'] ?? null);

            if (!empty($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $post->featured_image = $data['featured_image']->store('blog/featured', 'public');
            }

            $post->save();

            $this->syncSections($post, $data['sections'] ?? []);
            $this->syncTags($post, $data['tags'] ?? [], $data['new_tags'] ?? null);

            return $post;
        });
    }

    public function update(array $data, int $postId): BlogPost
    {
        return DB::transaction(function () use ($data, $postId) {
            $post = BlogPost::findOrFail($postId);

            $post->category_id      = $data['category_id'] ?? null;
            $post->title            = $data['title'];
            $post->excerpt          = $data['excerpt'] ?? null;
            $post->overview         = $data['overview'] ?? null;
            $post->meta_title       = $data['meta_title'] ?? null;
            $post->meta_description = $data['meta_description'] ?? null;
            $post->status           = $data['status'];
            $post->published_at     = $data['status'] === 'published'
                ? ($data['published_at'] ?? $post->published_at ?? now())
                : ($data['published_at'] ?? null);

            if (!empty($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $post->featured_image = $data['featured_image']->store('blog/featured', 'public');
            }

            $post->save();

            if (!empty($data['removed_section_ids'])) {
                BlogPostSection::whereIn('id', $data['removed_section_ids'])
                    ->where('post_id', $post->id)
                    ->delete();
            }

            if (!empty($data['removed_image_ids'])) {
                $images = BlogSectionImage::whereIn('id', $data['removed_image_ids'])->get();
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            $this->syncSections($post, $data['sections'] ?? []);
            $this->syncTags($post, $data['tags'] ?? [], $data['new_tags'] ?? null);

            return $post;
        });
    }

    protected function syncSections(BlogPost $post, array $sections): void
    {
        foreach ($sections as $index => $sectionData) {
            if (!empty($sectionData['id'])) {
                $section = BlogPostSection::where('id', $sectionData['id'])
                    ->where('post_id', $post->id)
                    ->first();
            } else {
                $section = new BlogPostSection();
                $section->post_id = $post->id;
            }

            if (!$section) {
                continue;
            }

            $section->heading    = $sectionData['heading'] ?? null;
            $section->content    = $sectionData['content'] ?? null;
            $section->list_items = !empty($sectionData['list_items'])
                ? array_values(array_filter($sectionData['list_items']))
                : null;
            $section->sort_order = $sectionData['sort_order'] ?? $index;
            $section->save();

            $files = $sectionData['images'] ?? [];
            foreach ($files as $imgIndex => $file) {
                if (!$file instanceof UploadedFile) {
                    continue;
                }
                $path = $file->store('blog/sections', 'public');

                BlogSectionImage::create([
                    'section_id' => $section->id,
                    'image_path' => $path,
                    'sort_order' => $imgIndex,
                ]);
            }
        }
    }

    protected function syncTags(BlogPost $post, array $tagIds, ?string $newTagsCsv): void
    {
        if ($newTagsCsv) {
            $names = array_filter(array_map('trim', explode(',', $newTagsCsv)));
            foreach ($names as $name) {
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $tagIds[] = $tag->id;
            }
        }

        $post->tags()->sync(array_unique($tagIds));
    }
}