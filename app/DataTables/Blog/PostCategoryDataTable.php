<?php

namespace App\DataTables\Blog;

use App\Models\Blog\PostCategory;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PostCategoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('total_posts', function ($row) {
                return $row->posts_count ?? 0;
            })

            ->addColumn('action', fn($row) => view('blog.category.action', compact('row')))

            ->rawColumns(['action']);
    }

    public function query(PostCategory $model)
    {
        return $model->newQuery()
            ->withCount('posts')
            ->select('post_categories.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('category-table')
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

            Column::make('name'),

            Column::make('slug'),

            Column::computed('total_posts')
                ->title('Posts'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Categories_' . date('YmdHis');
    }
}