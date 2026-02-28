<?php

namespace App\DataTables;

use App\Models\Type;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class TypeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if ($row->type_image) {
                    return '<img src="'.asset('storage/'.$row->type_image).'"
                            width="50" height="50"
                            style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Image</span>';
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
            ->addColumn('action', function ($row) {
                $edit = route('type.edit', $row->id);
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
            ->rawColumns(['image','status','action']);
    }

    public function query(Type $model)
    {
        return $model->newQuery()->select('types.*');
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

            Column::make('image')->title('Image')->orderable(false)->searchable(false),
            Column::make('type_name')->title('Type Name'),
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
        return 'Type_' . date('YmdHis');
    }
}