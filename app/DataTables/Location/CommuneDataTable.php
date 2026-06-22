<?php

namespace App\DataTables\Location;

use App\Models\Location\Commune;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class CommuneDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('province', fn($row) => $row->province_name ?? '')
            ->addColumn('district', fn($row) => $row->district_name ?? '')
            ->addColumn('action', fn($row) => view('location.communes.action', compact('row')))
            ->rawColumns(['action']);
    }

    public function query(Commune $model)
    {
        return $model->newQuery()
            ->leftJoin('districts', 'communes.district_id', 'districts.id')
            ->leftJoin('provinces', 'communes.province_id', 'provinces.id')
            ->select(
                'communes.id',
                'communes.district_id',
                'communes.province_id',
                'communes.name',
                'districts.name as district_name',
                'provinces.name as province_name'
            );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('commune-table')
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
                ->title('Commune Name'),

            Column::computed('province')
                ->title('Province'),

            Column::computed('district')
                ->title('District'),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'Communes_' . date('YmdHis');
    }
}