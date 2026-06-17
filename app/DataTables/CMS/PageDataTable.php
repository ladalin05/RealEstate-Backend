<?php

namespace App\DataTables\CMS;

use App\Models\Admin\Page;
use App\Models\Location\City;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PageDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('country', function ($row) {
                return $row->country->name ?? '';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';

                return '
                <div class="form-check form-switch">
                    <input type="checkbox"
                        class="form-check-input enable_disable"
                        data-id="'.$row->id.'"
                        '.$checked.'>
                </div>';
            })

            ->addColumn('action', fn($row) => view('location.cities.action', compact('row')))

            ->rawColumns(['status','action']);
    }

    public function query(Page $model)
    {
        return $model->newQuery()->select('pages.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('page-table')
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

            Column::make('page_title')
                ->title('Page Title'),

            Column::make('page_content')
                ->title('Content'),

            Column::make('page_title')
                ->title('Page Title'),

            Column::make('page_content')
                ->title('Content'),

            Column::make('status')
                ->title('Status')
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
        return 'Cities_' . date('YmdHis');
    }
}