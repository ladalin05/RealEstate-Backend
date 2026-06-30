<?php

namespace App\DataTables\Property;

use App\Models\Property\Amenity;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class AmenityDataTable extends DataTable
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
                        data-id="' . $row->id . '"
                        ' . $checked . '>
                </div>';
            })
            ->addColumn('action', fn($row) => view('property.amenities.action', compact('row'))->render())
            ->rawColumns(['status', 'action']);
    }

    public function query(Amenity $model)
    {
        return $model->newQuery()
            ->select('amenities.id', 'amenities.name_en', 'amenities.name_kh', 'amenities.status');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('amenities-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->selectStyleSingle()
            ->parameters([
                'responsive'  => true,
                'autoWidth'   => false,
                'pageLength'  => 25,
                'language'    => [
                    'search'            => '',
                    'searchPlaceholder' => 'Search amenities...',
                    'emptyTable'        => 'No amenities found.',
                ],
            ])
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
                ->orderable(false)
                ->width(40),

            Column::make('name_en')
                ->title('Name (EN)'),

            Column::make('name_kh')
                ->title('Name (KH)')
                ->defaultContent('—'),

            Column::computed('status')
                ->title('Status')
                ->orderable(false)
                ->searchable(false)
                ->width(80),

            Column::computed('action')
                ->title('Action')
                ->orderable(false)
                ->searchable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Amenities_' . date('YmdHis');
    }
}