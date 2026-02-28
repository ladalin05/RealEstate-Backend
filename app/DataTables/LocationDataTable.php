<?php

namespace App\DataTables;

use App\Models\Location;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class LocationDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
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
            ->addColumn('action', function ($row) {
                $edit = route('location.edit', $row->id);

                return '
                <div class="d-flex gap-2">
                    <a href="'.$edit.'" class="btn btn-sm btn-success" onclick="editlocation(event)">
                        <i class="fa fa-edit"></i>
                    </a>

                    <button class="btn btn-sm btn-danger data_remove"
                        data-id="'.$row->id.'">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['status','action']);
    }

    public function query(Location $model)
    {
        return $model->newQuery()->select('locations.*');
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('type-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
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
            Column::make('name')->title('Location Name'),
            Column::make('status')->title('Status')->orderable(false)->searchable(false),
            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
        ];
    }

    protected function filename(): string
    {
        return 'Locations_' . date('YmdHis');
    }
}