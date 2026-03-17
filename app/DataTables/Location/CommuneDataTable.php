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

            ->addColumn('district', function ($row) {
                return $row->district->name ?? '';
            })

            ->addColumn('city', function ($row) {
                return $row->district->city->name ?? '';
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

            ->addColumn('action', fn($row) => view('location.communes.action', compact('row')))

            ->rawColumns(['status','action']);
    }

    public function query(Commune $model)
    {
        return $model->newQuery()
            ->with(['district.city'])
            ->select('communes.*');
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

            Column::make('name')
                ->title('Commune Name'),

            Column::computed('district')
                ->title('District'),

            Column::computed('city')
                ->title('City'),

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
        return 'Communes_' . date('YmdHis');
    }
}