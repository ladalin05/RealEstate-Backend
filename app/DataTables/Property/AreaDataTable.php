<?php

namespace App\DataTables\Property;

use App\Models\Property\Area;
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
            ->addColumn('image', function ($row) {
                return $row->image
                    ? '<img src="' . e($row->image) . '" alt="' . e($row->name) . '" style="height:40px;width:40px;object-fit:cover;border-radius:4px;">'
                    : '<span class="text-muted">—</span>';
            })
            ->editColumn('name_en', fn($row) => $row->name_en ?? '—')
            ->editColumn('name_km', fn($row) => $row->name_km ?? '—')
            ->addColumn('province', fn($row) => $row->{'province_name_'.app()->getLocale()} ?? '')
            ->addColumn('district', fn($row) => $row->{'district_name_'.app()->getLocale()} ?? '')
            ->addColumn('commune', fn($row) => $row->{'commune_name_'.app()->getLocale()} ?? '')
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
            ->addColumn('action', fn($row) => view('property.areas.action', compact('row'))->render())
            ->rawColumns(['image', 'status', 'action']);
    }

    public function query(Area $model)
    {
        return $model->newQuery()
            ->leftJoin('provinces', 'areas.province_id', '=', 'provinces.id')
            ->leftJoin('districts', 'areas.district_id', '=', 'districts.id')
            ->leftJoin('communes', 'areas.commune_id', '=', 'communes.id')
            ->select(
                'areas.id',
                'areas.name_en',
                'areas.name_km',
                'areas.slug',
                'areas.zip_code',
                'areas.image',
                'areas.status',
                'provinces.name_en as province_name_en',
                'provinces.name_kh as province_name_kh',
                'districts.name_en as district_name_en',
                'districts.name_kh as district_name_kh',
                'communes.name_en as commune_name_en',
                'communes.name_kh as commune_name_kh'
            );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('areas-table')
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
                    'searchPlaceholder' => 'Search areas...',
                    'emptyTable'        => 'No areas found.',
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

            Column::computed('image')
                ->title('Image')
                ->orderable(false)
                ->searchable(false)
                ->width(80),

            Column::make('name_en')
                ->title('Name (EN)'),

            Column::make('name_km')
                ->title('Name (KH)'),

            Column::make('slug')
                ->title('Slug')
                ->defaultContent('—'),

            Column::make('zip_code')
                ->title('Zip Code')
                ->defaultContent('—'),

            Column::computed('status')
                ->title('Status')
                ->orderable(false)
                ->searchable(false)
                ->width(80),

            Column::computed('province')
                ->title('Province')
                ->orderable(false)
                ->searchable(false),

            Column::computed('district')
                ->title('District')
                ->orderable(false)
                ->searchable(false),

            Column::computed('commune')
                ->title('Commune')
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
        return 'Areas_' . date('YmdHis');
    }
}