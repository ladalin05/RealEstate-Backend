<?php

namespace App\DataTables\Property;

use App\Models\Property\Property;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PropertyDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('image', function ($row) {
                if ($row->main_image) {
                    return '<img src="'.asset('storage/'.$row->main_image).'"
                            width="60" height="60"
                            style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Image</span>';
            })

            ->addColumn('type', function ($row) {
                return $row->type?->name ?? '-';
            })

            ->addColumn('location', function ($row) {
                return $row->location?->name ?? '-';
            })

            ->editColumn('price', function ($row) {
                return $row->price ? '$' . number_format($row->price) : '-';
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

            ->addColumn('featured', function ($row) {
                return $row->featured
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })

            ->addColumn('action', fn($row) => view('property.properties.action', compact('row')))

            ->rawColumns(['image','status','featured','action']);
    }

    public function query(Property $model)
    {
        return $model->newQuery()
            ->with(['type','location'])
            ->select('properties.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('property-table')
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

            Column::computed('image')
                ->title('Image')
                ->searchable(false)
                ->orderable(false),

            Column::make('title')->title('Title'),

            Column::make('type')->title('Type'),

            Column::make('location')->title('Location'),

            Column::make('purpose')->title('Purpose'),

            Column::make('price')->title('Price'),

            Column::computed('featured')
                ->title('Featured')
                ->searchable(false)
                ->orderable(false),

            Column::computed('status')
                ->title('Status')
                ->searchable(false)
                ->orderable(false),

            Column::computed('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Properties_' . date('YmdHis');
    }
}