<?php

namespace App\DataTables;

use App\Models\Property;
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
                if ($row->image) {
                    return '<img src="'.asset('storage/'.$row->image).'"
                            width="60" height="60"
                            style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Image</span>';
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
            ->addColumn('action', function ($row) {

                $edit = route('property.edit', $row->id);

                return '
                <div class="d-flex gap-2">
                    <a href="'.$edit.'" class="btn btn-sm btn-success">
                        <i class="fa fa-edit"></i>
                    </a>

                    <button class="btn btn-sm btn-danger data_remove"
                        data-id="'.$row->id.'">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['image','status','featured','action']);
    }

    public function query(Property $model)
    {
        return $model->newQuery()->select('property.*');
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

            Column::make('image')
                ->title('Image')
                ->orderable(false)
                ->searchable(false),

            Column::make('title')->title('Title'),

            Column::make('price')->title('Price'),

            Column::make('purpose')->title('Purpose'),

            Column::make('featured')
                ->title('Featured')
                ->orderable(false)
                ->searchable(false),

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
        return 'Property_' . date('YmdHis');
    }
}