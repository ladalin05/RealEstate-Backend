<?php

namespace App\DataTables\Blog;

use App\Models\Blog\BlogPost;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class BlogPostDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('category_name', function ($row) {
                return $row->category_name ?? '';
            })
            ->editColumn('author_name', function ($row) {
                return $row->author_name ?? '';
            })
            ->addColumn('status', function ($row) {
                $badge = match ($row->status) {
                    'published' => 'success',
                    'draft' => 'secondary',
                    'archived' => 'dark',
                    default => 'light'
                };

                return '<span class="badge bg-'.$badge.'">'.$row->status.'</span>';
            })
            ->editColumn('image', function ($row) {
                $src = $row->main_image
                    ? rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $row->main_image
                    : null;

                return $src
                    ? '<img src="' . $src . '" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                    : '<span class="badge bg-light text-dark">No Image</span>';
            })
            ->addColumn('tags', function ($row) {
                return $row->tag_name ?? '';
            })
            ->editColumn('views_count', function ($row) {
                return number_format($row->views_count);
            })
            ->editColumn('published_at', function ($row) {
                return $row->published_at ? $row->published_at->format('Y-m-d H:i') : '';
            })
            ->addColumn('action', fn ($row) => view('blog.blog-post.action', compact('row')))
            ->rawColumns(['status', 'image', 'action']);
    }

    public function query(BlogPost $model)
    {
        return $model->newQuery()
            ->leftJoin('blog_categories', 'blog_posts.category_id', 'blog_categories.id')
            ->leftJoin('admins', 'blog_posts.author_id', 'admins.id')
            ->leftJoin('blog_post_tags', 'blog_posts.id', 'blog_post_tags.post_id')
            ->leftJoin('blog_tags', 'blog_post_tags.tag_id', 'blog_tags.id')
            ->select('blog_posts.*', 'blog_categories.name as category_name', 'admins.name as author_name', 'blog_tags.name as tag_name');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('blog-post-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    protected function getColumns()
    {
        return [

            Column::computed('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('title'),

            Column::make('category_name')
                ->title('Category'),

            Column::make('author_name')
                ->title('Author'),

            Column::make('status')
                ->orderable(false)
                ->searchable(false),

            Column::computed('tags')
                ->title('Tags')
                ->orderable(false)
                ->searchable(false),

            Column::make('views_count')
                ->title('Views'),

            Column::make('comments_count')
                ->title('Comments'),

            Column::make('published_at')
                ->title('Published'),

            Column::make('image')
                ->title('Image')
                ->orderable(false)
                ->searchable(false),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'BlogPosts_' . date('YmdHis');
    }
}