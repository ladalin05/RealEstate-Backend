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

            ->addColumn('property', fn($row) => $row->property_title ?? '')
            ->addColumn('country',  fn($row) => $row->country_name  ?? '')
            ->addColumn('province', fn($row) => $row->province_name ?? '')
            ->addColumn('district', fn($row) => $row->district_name ?? '')
            ->addColumn('commune',  fn($row) => $row->commune_name  ?? '')

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
        ->leftJoin('properties', 'property_locations.property_id', 'properties.id')
        ->leftJoin('countries', 'property_locations.country_id', 'countries.id')
        ->leftJoin('provinces', 'property_locations.province_id', 'provinces.id')
        ->leftJoin('districts', 'property_locations.district_id', 'districts.id')
        ->leftJoin('communes', 'property_locations.commune_id', 'communes.id')
        ->select('property_locations.*', 'properties.title as property_title', 'countries.name as country_name', 'provinces.name as province_name', 'districts.name as district_name', 'communes.name as commune_name');
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