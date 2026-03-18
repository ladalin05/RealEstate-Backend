<?php

namespace App\DataTables\Property;

use App\Models\Property\Property;
use Illuminate\Support\Facades\Storage;
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

            ->editColumn('image', function ($row) {
                if ($row->main_image && Storage::exists($row->main_image)) {
                    return '<img src="'.asset('storage/'.$row->main_image).'" 
                                width="60" height="60" 
                                style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Image</span>';
            })

            ->addColumn('type_name', function ($row) {
                return $row->type_name ?? '-';
            })

            ->addColumn('city_name', function ($row) {
                return $row->city_name ?? '-';
            })

            ->editColumn('price', function ($row) {
                return $row->price ? '$' . number_format($row->price) : '-';
            })

            ->editColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '
                <div class="form-check form-switch">
                    <input type="checkbox"
                        class="form-check-input enable_disable"
                        data-id="'.$row->id.'"
                        '.$checked.'>
                </div>';
            })

            ->editColumn('featured', function ($row) {
                return $row->featured == 1
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })

            ->addColumn('action', fn($row) => view('property.properties.action', compact('row')))

            ->rawColumns(['image','status','featured','action']);
    }

    public function query(Property $model)
    {
        $model = $model->newQuery()
                        ->join('property_type', 'property_type.id', '=', 'properties.type_id')
                        ->leftJoin('property_locations', 'property_locations.property_id', '=', 'properties.id')
                        ->leftJoin('cities', 'property_locations.country_id', '=', 'cities.id')
                        ->select(
                            'properties.*',
                            'cities.name as city_name',
                            'property_type.type_name as type_name'
                        );

        return $model;
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

            Column::make('type_name')->title('Type'),

            Column::make('city_name')->title('Location'),

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