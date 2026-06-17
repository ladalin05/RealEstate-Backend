<?php

namespace App\DataTables;

use App\Models\Location\PropertyLocation;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PropertyLocationDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('property', fn($row) => $row->property->title ?? '')
            ->addColumn('country',  fn($row) => $row->country->name  ?? '')
            ->addColumn('province', fn($row) => $row->province->name ?? '')
            ->addColumn('district', fn($row) => $row->district->name ?? '')
            ->addColumn('commune',  fn($row) => $row->commune->name  ?? '')

            ->addColumn('coordinates', function ($row) {
                if ($row->latitude && $row->longitude) {
                    return $row->latitude . ', ' . $row->longitude;
                }
                return '<span class="text-muted">—</span>';
            })

            ->addColumn('action', fn($row) => view('property-location.action', compact('row')))

            ->rawColumns(['coordinates', 'action']);
    }

    public function query(PropertyLocation $model)
    {
        return $model->newQuery()
            ->with(['property', 'country', 'province', 'district', 'commune'])
            ->select('property_locations.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('property-location-table')
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

            Column::computed('property')
                ->title('Property'),

            Column::computed('country')
                ->title('Country'),

            Column::computed('province')
                ->title('Province'),

            Column::computed('district')
                ->title('District'),

            Column::computed('commune')
                ->title('Commune'),

            Column::make('address')
                ->title('Address'),

            Column::computed('coordinates')
                ->title('Coordinates')
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
        return 'PropertyLocations_' . date('YmdHis');
    }
}