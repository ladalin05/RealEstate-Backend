<?php

namespace App\DataTables\Location;

use App\Models\Location\Area;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class AreaDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('province', fn($row) => $row->province_name ?? '')
            ->addColumn('district', fn($row) => $row->district_name ?? '')
            ->addColumn('commune', fn($row) => $row->commune_name ?? '')
            ->addColumn('level', function ($row) {
                if ($row->commune_id) return '<span class="badge bg-success">Commune</span>';
                if ($row->district_id) return '<span class="badge bg-info">District</span>';
                if ($row->province_id) return '<span class="badge bg-secondary">Province</span>';
                return '';
            })
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

            ->addColumn('action', fn($row) => view('location.areas.action', compact('row')))

            ->rawColumns(['level', 'status', 'action']);
    }

    public function query(Area $model)
    {
        return $model->newQuery()
            ->leftJoin('provinces', 'areas.province_id', 'provinces.id')
            ->leftJoin('districts', 'areas.district_id', 'districts.id')
            ->leftJoin('communes', 'areas.commune_id', 'communes.id')
            ->select(
                'areas.id',
                'areas.name_en',
                'areas.name_km',
                'areas.slug',
                'areas.status',
                'areas.province_id',
                'areas.district_id',
                'areas.commune_id',
                'provinces.name as province_name',
                'districts.name as district_name',
                'communes.name as commune_name'
            );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('area-table')
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
                ->title('Area Name'),

            Column::computed('level')
                ->title('Level'),

            Column::computed('province')
                ->title('Province'),

            Column::computed('district')
                ->title('District'),

            Column::computed('commune')
                ->title('Commune'),

            Column::computed('status')
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
        return 'Areas_' . date('YmdHis');
    }
}