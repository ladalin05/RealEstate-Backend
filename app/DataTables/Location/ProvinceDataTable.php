<?php

namespace App\DataTables\Location;

use App\Models\Location\Province;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ProvinceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('country', fn($row) => $row->country_name ?? '')
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';

                return '
                <div class="form-check form-switch">
                    <input type="checkbox"
                        class="form-check-input enable_disable"
                        data-id="' . $row->id . '"
                        ' . $checked . '>
                </div>';
            })

            ->addColumn('action', fn($row) => view('location.provinces.action', compact('row')))

            ->rawColumns(['status', 'action']);
    }

    public function query(Province $model)
    {
        return $model->newQuery()
            ->leftJoin('countries', 'provinces.country_id', 'countries.id')
            ->select('provinces.id', 'provinces.name', 'provinces.alt_name', 'provinces.country_id', 'countries.name as country_name');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('province-table')
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

            Column::make('name')
                ->title('Province Name'),

            Column::make('alt_name')
                ->title('Alt Name'),

            Column::computed('country')
                ->title('Country'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'Provinces_' . date('YmdHis');
    }
}