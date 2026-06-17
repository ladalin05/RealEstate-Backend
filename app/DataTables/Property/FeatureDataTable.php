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

            // Icon column — renders FontAwesome class or dash
            ->addColumn('icon', function ($row) {
                return $row->icon
                    ? '<i class="' . e($row->icon) . '" style="font-size:20px;"></i>'
                    : '<span class="text-muted">—</span>';
            })

            // Inline toggle — calls enable/disable route via JS
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

            ->addColumn('action', fn($row) =>
                view('property.features.action', compact('row'))->render()
            )

            ->rawColumns(['icon', 'status', 'action']);
    }

    public function query(Feature $model)
    {
        return $model->newQuery()
            ->select('features.id', 'features.name_en', 'features.name_kh', 'features.icon', 'features.status');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('features-table')
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
                    'searchPlaceholder' => 'Search features...',
                    'emptyTable'        => 'No features found.',
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

            Column::computed('icon')
                ->title('Icon')
                ->orderable(false)
                ->searchable(false)
                ->width(60),

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
        return 'Features_' . date('YmdHis');
    }
}