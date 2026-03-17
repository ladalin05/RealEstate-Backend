<?php

namespace App\DataTables\Property;

use App\Models\Property\Feature;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class FeatureDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('icon', function ($row) {
                if ($row->icon) {
                    return '<i class="'.$row->icon.'" style="font-size:20px;"></i>';
                }
                return '-';
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

            ->addColumn('action', fn($row) => view('property.features.action', compact('row')))

            ->rawColumns(['icon','status','action']);
    }

    public function query(Feature $model)
    {
        return $model->newQuery()->select('features.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('features-table')
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

            Column::make('name_en')->title('Name (EN)'),

            Column::make('name_kh')->title('Name (KH)'),

            Column::computed('icon')
                ->title('Icon')
                ->orderable(false)
                ->searchable(false),

            Column::computed('status')
                ->title('Status')
                ->orderable(false)
                ->searchable(false),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Features_' . date('YmdHis');
    }
}