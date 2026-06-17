<?php

namespace App\DataTables\Property;

use App\Models\Property\PropertyType;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class PropertyTypeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('image', function ($row) {
                $src = $row->image
                    ? rtrim(env('MINIO_ENDPOINT'), '/') . '/' . env('MINIO_BUCKET') . '/' . $row->image
                    : null;

                return $src
                    ? '<img src="' . $src . '" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                    : '<span class="badge bg-light text-dark">No Image</span>';
            })
            ->addColumn('name_en', fn($row) => $row->name_en)
            ->addColumn('name_kh', fn($row) => $row->name_kh ?? '-')
            ->addColumn('slug', fn($row) => $row->slug)
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
            ->addColumn('action', fn($row) => view('property.property-type.action', compact('row')))
            ->rawColumns(['image', 'status', 'action']);
    }

    public function query(PropertyType $model)
    {
        return $model->newQuery()->select('property_types.*');
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
            Column::make('name_en')->title('Name (EN)'),
            Column::make('name_kh')->title('Name (KH)'),
            Column::make('slug')->title('Slug'),
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