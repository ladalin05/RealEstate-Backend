<?php

namespace App\DataTables\Blog;

use App\Models\Blog\BlogCategory;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class BlogCategoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('total_posts', function ($row) {
                return $row->posts_count ?? 0;
            })
            ->addColumn('action', fn($row) => view('blog.blog-category.action', compact('row')))
            ->rawColumns(['status', 'action']);
    }

    public function query(BlogCategory $model)
    {
        return $model->newQuery()
            ->withCount('posts')
            ->select('blog_categories.*');
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

            Column::make('name_en')->title('Name (EN)'),
            Column::make('name_km')->title('Name (KM)'),

            Column::make('slug'),

            Column::computed('total_posts') ->title('Posts'),

            Column::computed('status') ->title('Status'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'BlogCategories_' . date('YmdHis');
    }
}