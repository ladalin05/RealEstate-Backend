<?php

namespace App\DataTables\Location;

use App\Models\Location\District;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class DistrictDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('province', fn($row) => $row->province->name ?? '')

            ->addColumn('action', fn($row) => view('location.districts.action', compact('row')))

            ->rawColumns(['action']);
    }

    public function query(District $model)
    {
        return $model->newQuery()
            ->with('province')
            ->select('districts.id', 'districts.province_id', 'districts.name');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('district-table')
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
                ->title('District Name'),

            Column::computed('province')
                ->title('Province'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'Districts_' . date('YmdHis');
    }
}