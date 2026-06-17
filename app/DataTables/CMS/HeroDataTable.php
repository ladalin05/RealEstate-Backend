<?php

namespace App\DataTables\CMS;

use App\Models\CMS\CmsHomeHero;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class HeroDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->editColumn('image_url', function ($row) {
                return $row->image_url
                    ? '<img src="' . asset($row->image_url) . '" width="80" class="img-thumbnail">'
                    : '-';
            })

            ->editColumn('status', function ($row) {
                return $row->status == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('admin.hero.edit', $row->id) . '" class="btn btn-sm btn-primary">
                        Edit
                    </a>
                ';
            })

            ->rawColumns(['image_url', 'status', 'action']);
    }

    public function query(CmsHomeHero $model)
    {
        return $model->newQuery()->select('cms_home_hero.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('hero-table')
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

            Column::make('image_url')
                ->title('Image'),

            Column::make('badge_en')
                ->title('Badge (EN)'),

            Column::make('badge_kh')
                ->title('Badge (KH)'),

            Column::make('title_main_en')
                ->title('Title Main (EN)'),

            Column::make('title_main_kh')
                ->title('Title Main (KH)'),

            Column::make('title_highlight_en')
                ->title('Highlight (EN)'),

            Column::make('title_highlight_kh')
                ->title('Highlight (KH)'),

            Column::make('status')
                ->title('Status'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'Hero_' . date('YmdHis');
    }
}