<?php

namespace App\DataTables\UserManagement;

use App\Models\UserManagement\Agency;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class AgencyDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()

            ->addColumn('logo', function ($row) {
                if ($row->logo) {
                    return '<img src="'.asset('storage/'.$row->logo).'"
                            width="50" height="50"
                            style="object-fit:cover;border-radius:6px;">';
                }
                return '<span class="badge bg-light text-dark">No Logo</span>';
            })

            ->addColumn('website', function ($row) {
                if ($row->website) {
                    return '<a href="'.$row->website.'" target="_blank">'.$row->website.'</a>';
                }
                return '-';
            })

            ->addColumn('action', fn($row) => view('user-management.agencies.action', compact('row')))

            ->rawColumns(['logo','website','action']);
    }

    public function query(Agency $model)
    {
        return $model->newQuery()->select('agencies.*');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('agencies-table')
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

            Column::computed('logo')
                ->title('Logo')
                ->searchable(false)
                ->orderable(false),

            Column::make('name')
                ->title('Agency Name'),

            Column::make('phone')
                ->title('Phone'),

            Column::make('email')
                ->title('Email'),

            Column::make('website')
                ->title('Website'),

            Column::make('address')
                ->title('Address'),

            Column::computed('action')
                ->title('Action')
                ->searchable(false)
                ->orderable(false)
                ->width(120),
        ];
    }

    protected function filename(): string
    {
        return 'Agencies_' . date('YmdHis');
    }
}