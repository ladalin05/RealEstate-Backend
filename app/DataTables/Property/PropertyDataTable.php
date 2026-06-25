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
            ->editColumn('image', function ($row) {
                $src = $row->main_image ?? null;

                return $src
                    ? '<img src="' . $src . '" width="60" height="60" style="object-fit:cover;border-radius:6px;">'
                    : '<span class="badge bg-light text-dark">No Image</span>';
            })
            ->addColumn('type_name', function ($row) {
                return $row->type_name ?? '-';
            })
            ->addColumn('location', function ($row) {
                $parts = array_filter([
                    $row->commune_name ?? null,
                    $row->district_name ?? null,
                    $row->province_name ?? null,
                ]);
                return $parts ? implode(', ', $parts) : '-';
            })
            ->editColumn('purpose', function ($row) {
                $map = [
                    'sale'      => ['label' => 'Sale',      'class' => 'bg-primary'],
                    'rent'      => ['label' => 'Rent',      'class' => 'bg-info text-dark'],
                    'sale_rent' => ['label' => 'Sale/Rent', 'class' => 'bg-warning text-dark'],
                ];
                $p = $map[$row->purpose] ?? ['label' => ucfirst($row->purpose), 'class' => 'bg-secondary'];
                return '<span class="badge ' . $p['class'] . '">' . $p['label'] . '</span>';
            })
            ->editColumn('price', function ($row) {
                if ($row->price_label) {
                    return $row->price_label;
                }
                if ($row->price) {
                    $symbol = $row->currency === 'KHR' ? '៛' : '$';
                    return $symbol . number_format($row->price);
                }
                return '-';
            })
            ->editColumn('status', function ($row) {
                $map = [
                    'active'   => 'bg-success',
                    'inactive' => 'bg-secondary',
                    'draft'    => 'bg-warning text-dark',
                    'sold'     => 'bg-danger',
                    'rented'   => 'bg-info text-dark',
                ];
                $class = $map[$row->status] ?? 'bg-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('featured', function ($row) {
                return $row->featured == 1
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })
            ->editColumn('verified', function ($row) {
                return $row->verified == 1
                    ? '<span class="badge bg-success"><i class="bi bi-patch-check"></i> Verified</span>'
                    : '<span class="badge bg-light text-dark">Unverified</span>';
            })
            ->addColumn('action', fn($row) => view('property.properties.action', compact('row')))

            ->rawColumns(['image', 'purpose', 'status', 'featured', 'verified', 'action']);
    }

    public function query(Property $model)
    {
        return $model->newQuery()
            ->join('property_types', 'property_types.id', '=', 'properties.type_id')
            ->leftJoin('property_locations', 'property_locations.property_id', '=', 'properties.id')
            ->leftJoin('provinces', 'provinces.id', '=', 'property_locations.province_id')
            ->leftJoin('districts', 'districts.id', '=', 'property_locations.district_id')
            ->leftJoin('communes', 'communes.id', '=', 'property_locations.commune_id')
            ->select(
                'properties.*',
                'property_types.name_en as type_name',
                'provinces.name as province_name',
                'districts.name as district_name',
                'communes.name as commune_name'
            );
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
                ->searchable(false)
                ->orderable(false)
                ->width(80),

            Column::make('title')
                ->title('Title'),

            Column::make('type_name')
                ->title('Type')
                ->searchable(false)
                ->orderable(false),

            Column::computed('location')
                ->title('Location')
                ->searchable(false)
                ->orderable(false),

            Column::make('purpose')
                ->title('Purpose')
                ->searchable(false)
                ->orderable(false),

            Column::make('price')
                ->title('Price'),

            Column::computed('featured')
                ->title('Featured')
                ->searchable(false)
                ->orderable(false)
                ->width(80),

            Column::computed('verified')
                ->title('Verified')
                ->searchable(false)
                ->orderable(false)
                ->width(90),

            Column::computed('status')
                ->title('Status')
                ->searchable(false)
                ->orderable(false)
                ->width(90),

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