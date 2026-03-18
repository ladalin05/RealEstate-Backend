<?php

namespace App\DataTables\Blog;

use App\Models\Blog\Post;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PostDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('category', function ($row) {
                return $row->category->name ?? '';
            })

            ->addColumn('author', function ($row) {
                return $row->author->name ?? '';
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

            ->addColumn('image', function ($row) {
                if ($row->featured_image && file_exists(public_path('storage/' . $row->featured_image))) {
                    return '<img src="'.asset('storage/'.$row->featured_image).'"
                            width="60" height="60"
                            style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Image</span>';
            })

            ->addColumn('action', fn($row) => view('blog.posts.action', compact('row')))

            ->rawColumns(['status','image','action']);
    }

    public function query(Post $model)
    {
        return $model->newQuery()
            ->with(['category','author'])
            ->select('posts.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('post-table')
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
                Button::make('reload')
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

            Column::computed('category')
                ->title('Category'),

            Column::computed('author')
                ->title('Author'),

            Column::make('status')
                ->orderable(false)
                ->searchable(false),

            Column::computed('image')
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
        return 'Posts_' . date('YmdHis');
    }
}